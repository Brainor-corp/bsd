<div class="form-group">
    <label>{{ $label }}</label>
    <div class="table-responsive">
        <input type="hidden" name="{{ "related[$name][foreignModel]" }}" value="{{ $relatedForeignModel }}">
        <input type="hidden" name="{{ "related[$name][foreignKey]" }}" value="{{ $relatedForeignKey }}">
        <table class="table table-hover table-bordered related-table" data-current-index="{{ count($relatedRows) }}">
            <thead class="thead-light">
                <tr>
                    @foreach($columns as $column)
                        <th class="align-middle">
                            {{ $column->getLabel() }}
                        </th>
                    @endforeach
                    <th class="text-center align-middle">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-success add-related-row"><i class="fas fa-plus"></i></button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="pattern-row d-none">
                    @foreach($columns as $column)
                        <td class="align-middle">
                            @php
                                $field = clone $column;
                                $currentRow = null;
                                $field->setFormIgnore(1);
                            @endphp

                            @if($field instanceof \Zeus\Admin\SectionBuilder\Form\Panel\Fields\Related)
                                {!! $field->render($relatedRows) !!}
                            @else
                                @php
                                    $value = $currentRow->{ $field->getName() } ?? null;
                                    if($value instanceof Countable)
                                    {
                                        $value = $value->pluck('id')->toArray();
                                    }

                                    $index = '@pattern@';
                                    $currentName = $field->getName();
                                    $field->setRelatedName("related[$name][rows][$index][$currentName]");
                                @endphp
                                {!! $field->render($value) !!}
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-danger remove-related-row"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-success add-related-row"><i class="fas fa-plus"></i></button>
                        </div>
                    </td>
                </tr>
                @foreach($relatedRows as $relatedRow)
                    <tr>
                        <input type="hidden" name="{{ "related[$name][rows][$loop->index][$relatedForeignKey]" }}" value="{{ $relatedRow->{$relatedForeignKey} }}">
                        @foreach($columns as $column)
                            <td class="align-middle">
                                @php
                                    $field = $column;
                                    $currentRow = method_exists($field, 'getRow') && !empty($field->getRow()) ? $field->getRow() : $relatedRow;
                                @endphp

                                @if($field instanceof \Zeus\Admin\SectionBuilder\Form\Panel\Fields\Related)
                                    {!! $field->render($relatedRows) !!}
                                @else
                                    @php
                                        $value = $currentRow->{ $field->getName() } ?? null;
                                        if($value instanceof Countable)
                                        {
                                            $value = $value->pluck('id')->toArray();
                                        }

                                        $index = $loop->parent->index;
                                        $currentName = $field->getName();
                                        $field->setRelatedName("related[$name][rows][$index][$currentName]");
                                    @endphp
                                    {!! $field->render($value) !!}
                                @endif
                            </td>
                        @endforeach
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-danger remove-related-row"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-success add-related-row"><i class="fas fa-plus"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>