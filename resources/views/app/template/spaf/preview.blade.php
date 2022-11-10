
@foreach($template->groups()->orderBy('sort')->get() as $group)
<div class="row mb-2">
    <h4>{{ $group->header }}</h4>
    <table class="table table-bordered">
        @foreach($group->questions()->orderBy('sort')->get() as $q)
        @if($q->next_line || $loop->iteration == 1)
            <tr>
                <td style="width: 30%;">{{ $q->text }}</td>
                <td style="width: 70%;">
                    <!-- <div class="row"> -->
                        @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : []])
                    <!-- </div> -->
        @else
            <div class="mt-1">
                @include('app.template.spaf.partial.question', ['q' => $q, 'answers' => isset($answers) ? $answers : []])
            </div>
        @endif

        @endforeach
    </table>
</div>

@endforeach