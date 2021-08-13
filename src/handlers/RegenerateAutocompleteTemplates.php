<?php

namespace nystudio107\autocomplete\handlers;

use Craft;

class RegenerateAutocompleteTemplates extends GenerateAutocompleteTemplates
{

    public function __invoke(\yii\base\Event $event): void
    {
        $autocompleteGenerators = $this->getAllGenerators();

        foreach($autocompleteGenerators as $class) {
            $generator = $this->resolve($class);
            $generator->beforeGenerate();
            $generator->regenerate();
        }
        Craft::info('Autocomplete templates re-generated',__METHOD__);
    }

}