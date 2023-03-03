
@foreach($template->groups()->orderBy('sort')->get() as $group)
<div class="row mb-2 {{ isset($displayed_on_schedule) ? $group->displayed_on_schedule == false ? 'd-none' : '' : '' }}">
    <h4>{{ $group->header }}</h4>
    <table class="table table-bordered">
        @foreach($group->questions()->orderBy('sort')->get() as $q)
        @if($q->next_line || $loop->iteration == 1)
            <tr >
                <td style="width: 30%; white-space:pre-wrap;">{{ $q->text }} {{ $q->required ? '* ' : '' }}</td>
                <td style="width: 70%;">
                @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : []])
        @else
            <div class="mt-1">
                @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : []])
            </div>
        @endif

        @endforeach
    </table>
</div>

@endforeach
