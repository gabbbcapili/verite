@if(! in_array($template->type, App\Models\Template::$forReport))
@foreach($template->groups()->orderBy('sort')->get() as $group)
<div class="row mb-2 {{ isset($displayed_on_schedule) ? $group->displayed_on_schedule == false ? 'd-none' : '' : '' }}">
    <h4>{{ $group->header }}</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            @foreach($group->questions()->orderBy('sort')->get() as $q)
            @if($q->next_line || $loop->iteration == 1)
                <tr >
                    <td style="width: 30%; white-space:pre-wrap;">{{ $q->text }} {{ $q->required ? '* ' : '' }}</td>
                    <td style="width: 70%;">
                    @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : [], 'g' => $group, 't' => $template])
            @else
                <div class="mt-1">
                    @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : [], 'g' => $group, 't' => $template])
                </div>
            @endif

            @endforeach
        </table>
    </div>
</div>
@endforeach
@else
    @foreach($template->groups()->orderBy('sort')->get() as $group)
        <div class="row mb-2">
            @foreach($group->questions()->orderBy('sort')->get() as $q)
                <div class="forInsertion">{!! $q->text !!}</div>
            @endforeach
        </div>
    @endforeach
@endif
