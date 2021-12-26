<?php

// This file has been auto-generated by the Symfony Cache Component.

return [[

'Symfony.Component.Form.Form' => 0,
'IteratorAggregate' => 1,
'Symfony.Component.Form.FormInterface' => 2,
'Symfony.Component.Form.ClearableErrorsInterface' => 3,
'Traversable' => 4,
'Countable' => 5,
'ArrayAccess' => 6,
'App.Form.Model.BookDto' => 7,
'App.Form.Model.CategoryDto' => 8,

], [

0 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
            clone (\Symfony\Component\VarExporter\Internal\Registry::$prototypes['Symfony\\Component\\Form\\Extension\\Validator\\Constraints\\Form'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Symfony\\Component\\Form\\Extension\\Validator\\Constraints\\Form')),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'Symfony\\Component\\Form\\Form',
                ],
                'defaultGroup' => [
                    'Form',
                ],
                'traversalStrategy' => [
                    2,
                ],
                'constraints' => [
                    [
                        $o[1],
                    ],
                ],
                'constraintsByGroup' => [
                    [
                        'Default' => [
                            $o[1],
                        ],
                        'Form' => [
                            $o[1],
                        ],
                    ],
                ],
                'groups' => [
                    1 => [
                        'Default',
                        'Form',
                    ],
                ],
            ],
        ],
        $o[0],
        []
    );
},
1 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'IteratorAggregate',
                ],
                'defaultGroup' => [
                    'IteratorAggregate',
                ],
            ],
        ],
        $o[0],
        []
    );
},
2 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'Symfony\\Component\\Form\\FormInterface',
                ],
                'defaultGroup' => [
                    'FormInterface',
                ],
            ],
        ],
        $o[0],
        []
    );
},
3 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'Symfony\\Component\\Form\\ClearableErrorsInterface',
                ],
                'defaultGroup' => [
                    'ClearableErrorsInterface',
                ],
            ],
        ],
        $o[0],
        []
    );
},
4 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'Traversable',
                ],
                'defaultGroup' => [
                    'Traversable',
                ],
            ],
        ],
        $o[0],
        []
    );
},
5 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'Countable',
                ],
                'defaultGroup' => [
                    'Countable',
                ],
            ],
        ],
        $o[0],
        []
    );
},
6 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (\Symfony\Component\VarExporter\Internal\Registry::$factories['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'ArrayAccess',
                ],
                'defaultGroup' => [
                    'ArrayAccess',
                ],
            ],
        ],
        $o[0],
        []
    );
},
7 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (($f = &\Symfony\Component\VarExporter\Internal\Registry::$factories)['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
            ($f['Symfony\\Component\\Validator\\Mapping\\PropertyMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\PropertyMetadata'))(),
            clone (($p = &\Symfony\Component\VarExporter\Internal\Registry::$prototypes)['Symfony\\Component\\Validator\\Constraints\\NotBlank'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Symfony\\Component\\Validator\\Constraints\\NotBlank')),
            clone ($p['Symfony\\Component\\Validator\\Constraints\\Length'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Symfony\\Component\\Validator\\Constraints\\Length')),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'App\\Form\\Model\\BookDto',
                    'title',
                ],
                'defaultGroup' => [
                    'BookDto',
                ],
                'members' => [
                    [
                        'title' => [
                            $o[1],
                        ],
                    ],
                ],
                'properties' => [
                    [
                        'title' => $o[1],
                    ],
                ],
                'class' => [
                    1 => 'App\\Form\\Model\\BookDto',
                ],
                'property' => [
                    1 => 'title',
                ],
                'constraints' => [
                    1 => [
                        $o[2],
                        $o[3],
                    ],
                ],
                'constraintsByGroup' => [
                    1 => [
                        'Default' => [
                            $o[2],
                            $o[3],
                        ],
                        'BookDto' => [
                            $o[2],
                            $o[3],
                        ],
                    ],
                ],
                'groups' => [
                    2 => [
                        'Default',
                        'BookDto',
                    ],
                    [
                        'Default',
                        'BookDto',
                    ],
                ],
                'maxMessage' => [
                    3 => 'The title cannot be longer than {{ limit }} characters',
                ],
                'minMessage' => [
                    3 => 'The title must be at least {{ limit }} characters long',
                ],
                'max' => [
                    3 => 250,
                ],
                'min' => [
                    3 => 5,
                ],
            ],
        ],
        $o[0],
        []
    );
},
8 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            (($f = &\Symfony\Component\VarExporter\Internal\Registry::$factories)['Symfony\\Component\\Validator\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\ClassMetadata'))(),
            ($f['Symfony\\Component\\Validator\\Mapping\\PropertyMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::f('Symfony\\Component\\Validator\\Mapping\\PropertyMetadata'))(),
            clone (($p = &\Symfony\Component\VarExporter\Internal\Registry::$prototypes)['Symfony\\Component\\Validator\\Constraints\\NotBlank'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Symfony\\Component\\Validator\\Constraints\\NotBlank')),
            clone ($p['Symfony\\Component\\Validator\\Constraints\\Length'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Symfony\\Component\\Validator\\Constraints\\Length')),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'App\\Form\\Model\\CategoryDto',
                    'name',
                ],
                'defaultGroup' => [
                    'CategoryDto',
                ],
                'members' => [
                    [
                        'name' => [
                            $o[1],
                        ],
                    ],
                ],
                'properties' => [
                    [
                        'name' => $o[1],
                    ],
                ],
                'class' => [
                    1 => 'App\\Form\\Model\\CategoryDto',
                ],
                'property' => [
                    1 => 'name',
                ],
                'constraints' => [
                    1 => [
                        $o[2],
                        $o[3],
                    ],
                ],
                'constraintsByGroup' => [
                    1 => [
                        'Default' => [
                            $o[2],
                            $o[3],
                        ],
                        'CategoryDto' => [
                            $o[2],
                            $o[3],
                        ],
                    ],
                ],
                'groups' => [
                    2 => [
                        'Default',
                        'CategoryDto',
                    ],
                    [
                        'Default',
                        'CategoryDto',
                    ],
                ],
                'maxMessage' => [
                    3 => 'The name cannot be longer than {{ limit }} characters',
                ],
                'minMessage' => [
                    3 => 'The name must be at least {{ limit }} characters long',
                ],
                'max' => [
                    3 => 250,
                ],
                'min' => [
                    3 => 5,
                ],
            ],
        ],
        $o[0],
        []
    );
},

]];
