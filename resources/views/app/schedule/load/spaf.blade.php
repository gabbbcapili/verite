@foreach($spafs as $spaf)
    @include('app.template.spaf.preview', ['template' => $spaf->template, 'answers' => $spaf->answers, 'disabled' => true, 'displayed_on_schedule' => true])
@endforeach
