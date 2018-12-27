<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Пациенты', 'options' => ['class' => 'header']],
                    ['label' => 'Записи на прием', 'url' => ['/appointments']],
                    ['label' => 'Все пациенты', 'url' => ['/patients']],

                    [
                        'label' => 'Компания',
                        'icon' => 'circle-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Клиники', 'url' => ['/patients']],
                            ['label' => 'Врачи', 'url' => ['/patients']],
                        ],
                    ],

                    ['label' => 'Управление CRM', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->can('manageUsers')],
                    ['label' => 'Пользователи', 'url' => ['/users'], 'visible' => Yii::$app->user->can('manageUsers')],

                    ['label' => 'Выход', 'options' => ['class' => 'header']],
                    ['label' => 'Logout', 'url' => ['site/logout']],

                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],

                ],
            ]
        ) ?>

    </section>

</aside>
