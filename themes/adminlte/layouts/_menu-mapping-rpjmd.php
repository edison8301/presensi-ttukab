<?php 

use dmstr\widgets\Menu;

echo Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'items' => [
        ['label' => 'kinerja Tahunan', 'icon' => 'folder', 'url' => ['/kinerja/kegiatan-tahunan/index-v2']],
    ],
]);