@php
    $answer = null;
    $updated_at = null;
    if($answers){
        if($answers->where('question_id', $q->id)->first()){
            $answer = $answers->where('question_id', $q->id)->first()->value;
            $updated_at = $answers->where('question_id', $q->id)->first()->updated_at;
        }
    }
@endphp

@if($q->type == 'input' || $q->type == 'email' || $q->type == 'number')
    <input type="text" name="question[{{ $q->id  }}]" id="question.{{ $q->id }}" class="form-control" placeholder="{{ $q->text }}" value="{{ $answer ? $answer : '' }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif {{ isset($disabled) ? 'disabled' : '' }}>
@elseif($q->type == 'textarea')
    <textarea name="question[{{ $q->id  }}]" id="question.{{ $q->id }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif class="form-control" placeholder="{{ $q->text }}" {{ isset($disabled) ? 'disabled' : '' }}>{{ $answer ? $answer : '' }}</textarea>
@elseif($q->type == 'title')

@elseif($q->type == 'radio')
    <input type="hidden" id="question.{{ $q->id }}">
    @foreach(explode('|', $q->for_checkbox) as $option)
    <div class="form-check form-check-inline" @if(isset($updated_at)) data-bs-toggle="tooltip" title="Updated At: {{ $updated_at->diffForHumans() }}" @endif>
          <input class="form-check-input"
           type="radio" name="question[{{ $q->id  }}]" id="question.{{ $q->id}}.{{ $option }}"
            value="{{ $option }}" {{ $option == $answer ? 'checked' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="question.{{ $q->id}}.{{ $option }}" style="white-space:pre-wrap;">{{ $option }}</label>
    </div>
    @endforeach
@elseif($q->type == 'checkbox')
    <input type="hidden" id="checkbox.{{ $q->id}}">
    @foreach(explode('|', $q->for_checkbox) as $option)
        <div class="form-check form-check-inline" @if(isset($updated_at)) data-bs-toggle="tooltip" title="Updated At: {{ $updated_at->diffForHumans() }}" @endif>
          <input class="form-check-input" type="checkbox"
           name="checkbox[{{$q->id}}][]" id="checkbox.{{ $q->id}}.{{ $option }}"
           value="{{ $option }}" {{ $answer ? in_array($option, explode(',', $answer)) ? 'checked' : '' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="checkbox.{{ $q->id}}.{{ $option }}" style="white-space:pre-wrap;">{{ $option }}</label>
        </div>
    @endforeach
@endif
