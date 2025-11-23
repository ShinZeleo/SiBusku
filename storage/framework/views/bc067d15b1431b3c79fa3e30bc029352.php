<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold leading-5 text-blue-700 bg-blue-50/80 backdrop-blur-sm border border-blue-200/50 focus:outline-none transition-all duration-200 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium leading-5 text-gray-600 hover:text-gray-900 hover:bg-white/50 backdrop-blur-sm border border-transparent hover:border-gray-200/50 focus:outline-none transition-all duration-200 ease-in-out';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/components/nav-link.blade.php ENDPATH**/ ?>