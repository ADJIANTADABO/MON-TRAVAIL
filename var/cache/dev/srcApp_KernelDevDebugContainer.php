<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerKXwxN0k\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerKXwxN0k/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerKXwxN0k.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerKXwxN0k\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerKXwxN0k\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'KXwxN0k',
    'container.build_id' => '8cea4fb6',
    'container.build_time' => 1564871734,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerKXwxN0k');
