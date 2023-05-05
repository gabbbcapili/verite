@foreach($template->groups()->orderBy('sort')->get() as $group)
<li
    class="
    list-group-item
    list-group-item-action
    list-group-item-info
    group-list
    "
    id="{{ $group->id }}"
    aria-controls="list-{{ $group->id }}"

    href="#group{{ $group->id }}" role="tab"
     title="{{ $group->header }}"
>
    {{ \Illuminate\Support\Str::limit($group->header, 25, $end='...') }}
    <div class="d-flex justify-content-end">
        <div class="btn-group justify-conte" role="group">
            @if($group->editable)
            <button type="button" class="btn btn-sm btn-outline-success modal_button" data-action="{{ route('template.group.delete', $group) }}"  title="Delete"><i data-feather="delete"></i></button>
            <button type="button" class="btn btn-sm btn-outline-success modal_button" data-action="{{ route('template.group.edit', $group) }}"  title="Edit"><i data-feather="edit"></i></button>
            @endif
            <button type="button" class="btn btn-sm btn-outline-success confirm" data-action="{{ route('template.group.clone', $group) }}" data-title="Are you sure to clone this group {{ $group->header }}?"  title="Clone"><i data-feather="copy"></i></button>
            <span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon"  title="Move"><i class="" data-feather="move"></i></span>
        </div>
    </div>
</li>

@endforeach
