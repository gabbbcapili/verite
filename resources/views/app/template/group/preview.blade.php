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
    data-bs-toggle="tooltip" title="{{ $group->header }}"
>
    {{ \Illuminate\Support\Str::limit($group->header, 25, $end='...') }}
    <div class="d-flex justify-content-end">
        <div class="btn-group justify-conte" role="group">
            <button type="button" class="btn btn-sm btn-outline-success modal_button" data-action="{{ route('template.group.delete', $group) }}" data-bs-toggle="tooltip" title="Delete"><i data-feather="delete"></i></button>
            <button type="button" class="btn btn-sm btn-outline-success modal_button" data-action="{{ route('template.group.edit', $group) }}" data-bs-toggle="tooltip" title="Edit"><i data-feather="edit"></i></button>
            <span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon" data-bs-toggle="tooltip" title="Move"><i class="" data-feather="move"></i></span>
        </div>
    </div>
</li>

@endforeach
