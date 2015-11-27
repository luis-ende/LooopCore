<?php

namespace LooopCore\FrameworkBundle\Builder\Form\Events;

final class FormBuilderEvents
{
    const PRE_BUILD_FORM = 'formbuilder.pre_build';    
    const POST_BUILD_FORM = 'formbuilder.post_build';    
    const EMPTY_DATA_FORM = 'formbuilder.empty_data';
    const BUILDER_SAVE_DATA = 'formbuilder.save_data';
}
