<div>
    @include(
        'components.form.label',
        [
            'for' => $name, 
            'name' => $label
        ]
    )

    <div class="mt-2">
    {{ Form::textarea($name,$value, 
                                [
                                    'class' => 'w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                                    ...$attributes
                                ]) }}
    </div>
    @include(
        'components.form.error',
        [ 
            'name' => $name
        ]
    )
</div>