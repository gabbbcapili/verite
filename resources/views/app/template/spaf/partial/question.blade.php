@php
    $answer = null;
    if($answers){
        if($answers->where('question_id', $q->id)->first()){
            $answer = $answers->where('question_id', $q->id)->first()->value;
        }
    }
@endphp

@if($q->type == 'input')
    <input type="text" name="question[{{ $q->id  }}]" id="question.{{ $q->id }}" class="form-control" placeholder="{{ $q->text }}" value="{{ $answer ? $answer : '' }}" {{ isset($disabled) ? 'disabled' : '' }}>
@elseif($q->type == 'radio')
    <input type="hidden" id="question.{{ $q->id }}">
    @foreach(explode(',', $q->for_checkbox) as $option)
    <div class="form-check form-check-inline">
          <input class="form-check-input"
           type="radio" name="question[{{ $q->id  }}]" id="question.{{ $q->id}}.{{ $option }}"
            value="{{ $option }}" {{ $option == $answer ? 'checked' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="question.{{ $q->id}}.{{ $option }}">{{ $option }}</label>
    </div>
    @endforeach
@elseif($q->type == 'checkbox')
    <input type="hidden" id="checkbox.{{ $q->id}}">
    @foreach(explode(',', $q->for_checkbox) as $option)
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox"
           name="checkbox[{{$q->id}}][]" id="checkbox.{{ $q->id}}.{{ $option }}"
           value="{{ $option }}" {{ $answer ? in_array($option, explode(',', $answer)) ? 'checked' : '' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="checkbox.{{ $q->id}}.{{ $option }}">{{ $option }}</label>
        </div>
    @endforeach
@endif
