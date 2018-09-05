<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Quizes',
                        'icon' => 'question',
                        'items' => [
                            ['label' => 'All', 'icon' => 'bars', 'url' => ['quizes/index'],],
                            ['label' => 'Create new', 'icon' => 'plus', 'url' => ['quizes/create'],],
                        ],
                    ],
                    [
                        'label' => 'Users',
                        'icon' => 'user',
                        'url' => ['users/index']
                    ],
                    [
                        'label' => 'Settings',
                        'icon' => 'gear',
                        'url' => ['settings/index']
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
