<?php

/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace LooopCore\CommonsBundle\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Doctrine\ORM\Tools\Console\MetadataFilter,
    Doctrine\ORM\Tools\EntityGenerator,
    Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;

/**
 * Command to generate entity classes and method stubs from your mapping information.
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link    www.doctrine-project.org
 * @since   2.0
 * @version $Revision$
 * @author  Benjamin Eberlei <kontakt@beberlei.de>
 * @author  Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author  Jonathan Wage <jonwage@gmail.com>
 * @author  Roman Borschel <roman@code-factory.org>
 */
class GenerateSuperEntitiesCommand extends \Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand {

    /**
     * @see Console\Command\Command
     */
    protected function configure() {
        $this
                ->setName('orm:generate-super-entities')
                ->setDescription('Generate entity superclasses with method stubs and subclasses from your mapping information.')
                ->setDefinition(array(
                    new InputOption(
                            'filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be processed.'
                    ),
                    new InputArgument(
                            'dest-path', InputArgument::REQUIRED, 'The path to generate your entity subclasses.'
                    ),
                    new InputOption(
                            'super-dest-path', null, InputOption::VALUE_REQUIRED, 'The path to save the created (superclass-)entities', false
                    ),
                    new InputOption(
                            'super-dest-namespace', null, InputOption::VALUE_REQUIRED, 'The namespace of the created (superclass-)entities', false
                    ),
                    new InputOption(
                            'base-trait', null, InputOption::VALUE_OPTIONAL, 'The base trait class to use for all entities (if any)', false
                    ),
                    new InputOption(
                            'base-interface', null, InputOption::VALUE_OPTIONAL, 'The base interface class to use for all entities (if any)', false
                    ),
                    new InputOption(
                            'generate-annotations', null, InputOption::VALUE_OPTIONAL, 'Flag to define if generator should generate annotation metadata on entities.', false
                    ),
                    new InputOption(
                            'generate-methods', null, InputOption::VALUE_OPTIONAL, 'Flag to define if generator should generate stub methods on entities.', true
                    ),
                    new InputOption(
                            'regenerate-entities', null, InputOption::VALUE_OPTIONAL, 'Flag to define if generator should regenerate entity if it exists.', false
                    ),
                    new InputOption(
                            'update-entities', null, InputOption::VALUE_OPTIONAL, 'Flag to define if generator should only update entity if it exists.', true
                    ),
                    new InputOption(
                            'extend', null, InputOption::VALUE_OPTIONAL, 'Defines a base class to be extended by generated entity classes.'
                    ),
                    new InputOption(
                            'num-spaces', null, InputOption::VALUE_OPTIONAL, 'Defines the number of indentation spaces', 4
                    )
                ))
                ->setHelp(<<<EOT
Generate entity classes and method stubs from your mapping information.

If you use the <comment>--update-entities</comment> or <comment>--regenerate-entities</comment> flags your exisiting
code gets overwritten. The EntityGenerator will only append new code to your
file and will not delete the old code. However this approach may still be prone
to error and we suggest you use code repositories such as GIT or SVN to make
backups of your code.

It makes sense to generate the entity code if you are using entities as Data
Access Objects only and dont put much additional logic on them. If you are
however putting much more logic on the entities you should refrain from using
the entity-generator and code your entities manually.

<error>Important:</error> Even if you specified Inheritance options in your
XML or YAML Mapping files the generator cannot generate the base and
child classes for you correctly, because it doesn't know which
class is supposed to extend which. You have to adjust the entity
code manually for inheritance to work!
EOT
        );
    }

    /**
     * delete a directory recurively
     */
    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * @see Console\Command\Command
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getManager();
        //$em = $this->getHelper('em')->getEntityManager();

        // disable replacement of table names in metadata
        //\Entity\Base\LoadMetadataEvent::setSkipEvent(true);

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
        $metadatas = $cmf->getAllMetadata();
        $metadatas = MetadataFilter::filter($metadatas, $input->getOption('filter'));

        // Process destination directory
        $subDestPath = realpath($input->getArgument('dest-path'));
        $superDestNamespace = $input->getOption('super-dest-namespace');
        $baseTrait = $input->getOption('base-trait');
        $baseInterface = $input->getOption('base-interface');
        $tmpPath = realpath($input->getOption('super-dest-path')) . DIRECTORY_SEPARATOR . "tmp";
        if (!file_exists($tmpPath)) {
            mkdir($tmpPath);
        }
        $createdDestPath = realpath($input->getOption('super-dest-path') . DIRECTORY_SEPARATOR);

        if (!file_exists($createdDestPath)) {
            throw new \InvalidArgumentException(
            sprintf("Created Super Entities destination directory '<info>%s</info>' does not exist.", $createdDestPath)
            );
        } else if (!is_writable($createdDestPath)) {
            throw new \InvalidArgumentException(
            sprintf("Created super Entities destination directory '<info>%s</info>' does not have write permissions.", $destPath)
            );
        }

        if (count($metadatas)) {
            // Create EntityGenerator
            $entityGenerator = new EntityGenerator();

            $entityGenerator->setGenerateAnnotations($input->getOption('generate-annotations'));
            $entityGenerator->setGenerateStubMethods($input->getOption('generate-methods'));
            $entityGenerator->setRegenerateEntityIfExists($input->getOption('regenerate-entities'));
            $entityGenerator->setUpdateEntityIfExists($input->getOption('update-entities'));
            $entityGenerator->setNumSpaces($input->getOption('num-spaces'));
            $entityGenerator->setFieldVisibility("protected");

            if (($extend = $input->getOption('extend')) !== null) {
                $entityGenerator->setClassToExtend($extend);
            }

            foreach ($metadatas as $metadata) {
                /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
                $metadata->setLifecycleCallbacks([]);
                $output->write(
                        sprintf('Processing entity "<info>%s</info>"', $metadata->name) . PHP_EOL
                );
            }
            
            // Problem: Doctrine is now so intelligent that it looks at traits and excludes
            // all properties defined in traits. BUT the generated file is included as a trait,
            // so only an empty generated trait is created the 2nd time.
            // Solution: remove the body of the trait, to that it can be re-created.
            foreach ($metadatas as $metadata) {
                // WARNING: duplicated code from below
                // TODO: refactor
                $classname = $metadata->name;
                $namespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${1}', $classname);
                $classnameWithoutNamespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${2}', $classname);
                $filePath = $tmpPath . DIRECTORY_SEPARATOR .
                        str_replace('\\', DIRECTORY_SEPARATOR, $classname) . ".php";
                
                $new_file_path = $classnameWithoutNamespace . ".php";
                $new_file_path = $createdDestPath . DIRECTORY_SEPARATOR 
                        . "$namespace\\$superDestNamespace\\$new_file_path";
                $new_file_path = str_replace('\\', DIRECTORY_SEPARATOR, $new_file_path);
                if (file_exists($new_file_path)) {
                    $fileContents = file_get_contents($new_file_path);
                    $fileContentsLines = explode("\n", $fileContents);
                    $newFileContent = "";
                    foreach(range(0,16) as $line) {
                        $newFileContent .= $fileContentsLines[$line] . "\n";
                    }
                    $newFileContent .= "}";
                    
                    file_put_contents($new_file_path, $newFileContent);
                }
            }

            // Generating Entities
            $entityGenerator->generate($metadatas, $tmpPath);
            
            foreach ($metadatas as $metadata) {
                $classname = $metadata->name;
                $namespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${1}', $classname);
                $classnameWithoutNamespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${2}', $classname);
                $filePath = $tmpPath . DIRECTORY_SEPARATOR .
                        str_replace('\\', DIRECTORY_SEPARATOR, $classname) . ".php";
                
                $fileContents = file_get_contents($filePath);
                // replace namespace in file contents
                $newFileContents = preg_replace("/\nnamespace " . str_replace("\\", "\\\\", $namespace) . "/", "\nnamespace $namespace\\$superDestNamespace", $fileContents);
                $newFileContents = preg_replace("/class ($classnameWithoutNamespace.*)/", 'trait ${1}', $newFileContents);
                
                $new_file_path = $classnameWithoutNamespace . ".php";
                $new_file_path = $createdDestPath . DIRECTORY_SEPARATOR 
                        . "$namespace\\$superDestNamespace\\$new_file_path";
                $new_file_path = str_replace('\\', DIRECTORY_SEPARATOR, $new_file_path);
                if (!file_exists(dirname($new_file_path))) {
                    mkdir(dirname($new_file_path));
                }
                
                $repository = $metadata->getMetadataValue("customRepositoryClassName");
                if (!$repository) {
                    $repository = "\\Doctrine\\ORM\\EntityRepository";
                } else {
                    $repository = "\\" . $repository;
                }
                
                $newFileContentsLines = explode("\n", $newFileContents);
                $newFileContentsLines[8] .= "\n"
                        . "    /**\n"
                        . "      * Returns the Repository that corresponds to this Entity.\n"
                        . "      * \n"
                        . "      * @return $repository\n"
                        . "      */\n"
                        . "     public static function getRepository() {\n"
                        . "         return self::__getRepository();\n"
                        . "     }\n\n";
                $newFileContents = implode("\n", $newFileContentsLines);

                    
                file_put_contents($new_file_path, $newFileContents);
                //unlink($filePath);
            }

            // Outputting information message
            $output->write(PHP_EOL . sprintf('Entity classes generated to "<info>%s</INFO>"', $createdDestPath) . PHP_EOL);

            // create stubs for models, if they do not exist

            foreach ($metadatas as $metadata) {
                $classname = $metadata->name;
                $namespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${1}', $classname);
                $classnameWithoutNamespace = preg_replace('/^(.*)\\\\([^\\\\]*)$/', '${2}', $classname);

                $stubPath = $subDestPath . DIRECTORY_SEPARATOR .
                        str_replace('\\', DIRECTORY_SEPARATOR, $namespace . DIRECTORY_SEPARATOR . $classnameWithoutNamespace) . ".php";

                if (!file_exists($stubPath)) {
                    if (!file_exists(dirname($stubPath))) {
                        mkdir(dirname($stubPath));
                    }
                    $stubContents = "<?php\n\n"
                            . "namespace $namespace;\n\n"
                            . "/**\n"
                            . " * Entity (model class) $classnameWithoutNamespace\n\n"
                            . " * This class uses the generated trait $classnameWithoutNamespace ans has all the attributes,\n"
                            . " * that are also present in the database or mapping.\n\n"
                            . " * This class is for methods, not directly coming from the DB\n"
                            . " */\n"
                            //. "class $classnameWithoutNamespace extends $superDestNamespace\\$classnameWithoutNamespace\n"
                            . "class $classnameWithoutNamespace";
                    
                    if ($baseInterface) {
                        $stubContents .= " implements $baseInterface";
                    }
                    $stubContents .= " {\n"
                            . "    use $superDestNamespace\\$classnameWithoutNamespace;\n";
                    
                    if ($baseTrait) {
                        $stubContents .= "    use $baseTrait;\n";
                    }
//                    $stubContents .= "\n    /** Find an object by ID\n"
//                            . "     * @return $classnameWithoutNamespace\n"
//                            . "     */\n"
//                            . "    public static function find(\$id, \$lockMode = 0, \$lockVersion = null) {\n"
//                            . "        return parent::find(\$id, \$lockMode, \$lockVersion);\n"
//                            . "    }\n";
                    $stubContents .= "\n}";
                    file_put_contents($stubPath, $stubContents);
                    $output->write(PHP_EOL . sprintf('Class stub created in "<info>%s</INFO>"', $stubPath) . PHP_EOL);
                }
            }
            self::delTree($tmpPath);
        } else {
            $output->write('No Metadata Classes to process.' . PHP_EOL);
        }
    }

}
