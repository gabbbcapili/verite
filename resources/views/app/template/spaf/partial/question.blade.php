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
    <input type="text" name="question[{{ $q->id  }}]" id="question.{{ $q->id }}" class="form-control forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}" placeholder="{{ $q->text }}{{ $q->required ? '* ' : '' }}" value="{{ $answer ? $answer : '' }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif {{ isset($disabled) ? 'disabled' : '' }}>
@elseif($q->type == 'textarea')
    <textarea name="question[{{ $q->id  }}]" id="question.{{ $q->id }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif class="form-control forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}" placeholder="{{ $q->text }}{{ $q->required ? '* ' : '' }}" {{ isset($disabled) ? 'disabled' : '' }}>{{ $answer ? $answer : '' }}</textarea>
@elseif($q->type == 'file')
    @if(!$q->next_line)<label>{{ $q->text }}{{ $q->required ? '* ' : '' }}</label>@endif
    <input type="file" name="file[{{ $q->id  }}]" id="file.{{ $q->id }}" class="form-control forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}" placeholder="{{ $q->text }}{{ $q->required ? '* ' : '' }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif {{ isset($disabled) ? 'disabled' : '' }}>
    @if($answer)<div><a target="_blank" href="{{ asset('uploads/spaf/'. $answer)  }}">{{ $answer }}</a></div>@endif
@elseif($q->type == 'file_multiple')
    @if(!$q->next_line)<label>{{ $q->text }}{{ $q->required ? '* ' : '' }}</label>@endif
    <input type="file" multiple="multiple" name="file_multiple[{{ $q->id  }}][]" id="file_multiple.{{ $q->id }}" class="form-control forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}" placeholder="{{ $q->text }}{{ $q->required ? '* ' : '' }}"@if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif {{ isset($disabled) ? 'disabled' : '' }}>
    @if($answer)
        @foreach(explode(',', $answer) as $file)
            <div><a target="_blank" href="{{ asset('uploads/spaf/'. $file)  }}">{{ $file }}</a></div>
        @endforeach

    @endif
@elseif($q->type == 'title')

@elseif($q->type == 'radio')
    <input type="hidden" id="question.{{ $q->id }}">
    @foreach(explode('|', $q->for_checkbox) as $option)
    <div class="form-check form-check-inline" @if(isset($updated_at)) data-bs-toggle="tooltip" title="Updated At: {{ $updated_at->diffForHumans() }}" @endif>
          <input checkstate="{{ $option == $answer ? 'true' : '' }}" data-input="question[{{ $q->id  }}]" class="form-check-input forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}"
           type="radio" name="question[{{ $q->id  }}]" id="question.{{ $q->id}}.{{ $option }}"
            value="{{ $option }}" {{ $option == $answer ? 'checked' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="question.{{ $q->id}}.{{ $option }}" style="white-space:pre-wrap;">{{ $option }}</label>
    </div>
    @endforeach
    <input type="hidden" name="question[{{ $q->id  }}]" class="radioButtonInput" value="{{ $answer }}">
@elseif($q->type == 'checkbox')
    <input type="hidden" id="checkbox.{{ $q->id}}">
    @foreach(explode('|', $q->for_checkbox) as $option)
        <div class="form-check form-check-inline" @if(isset($updated_at)) data-bs-toggle="tooltip" title="Updated At: {{ $updated_at->diffForHumans() }}" @endif>
          <input class="form-check-input forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_{{$g->header}}_{{$q->text}}}" type="checkbox"
           name="checkbox[{{$q->id}}][]" id="checkbox.{{ $q->id}}.{{ $option }}"
           value="{{ $option }}" {{ $answer ? in_array($option, explode(',', $answer)) ? 'checked' : '' : '' }} {{ isset($disabled) ? 'disabled' : '' }} />
          <label class="form-check-label" for="checkbox.{{ $q->id}}.{{ $option }}" style="white-space:pre-wrap;">{{ $option }}</label>
        </div>
    @endforeach
@elseif($q->type == 'table')
    @if(!$q->next_line)<label>{{ $q->text }}{{ $q->required ? '* ' : '' }}</label>@endif
    <table class="table text-center table-sm table-striped table-hover table-bordered" id="table.{{ $q->id }}">
        <thead class="forInsertion withDataInsertion" data-forinsertion="{template-{{ $t->id }}_{{$q->id}}_{{$t->name}}_TABLE_{{$g->header}}_{{$q->text}}}"  data-forinsertionReport="{{ $q->convertToTinyMceTable($answer) }}">
            <tr>
                <th class="cursor-pointer addQuestionTableRow" data-table="table.{{ $q->id }}" data-table-id="{{ $q->id }}" data-count="0" data-columns-count="{{ count(explode('|', $q->for_checkbox)) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Row"><i data-feather="plus"></i></th>
               @foreach(explode('|', $q->for_checkbox) as $column)
                <th>{{ $column }}</th>
               @endforeach
            </tr>
        </thead>
        <tbody>
            @if($answer)
                @foreach(json_decode($answer) as $index => $row)
                    <tr>
                        <td>@if(! isset($disabled))<div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success deleteQuestionRow" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button></div></div>@endif</td>
                        @foreach($row as $column)
                            <td><input class="form-control forInsertion" type="text" name="table[{{ $q->id }}][{{ $index + 1000 }}][{{ $loop->iteration }}]" value="{{ $column }}" @if(isset($updated_at)) title="Updated At: {{ $updated_at->diffForHumans() }}" @endif {{ isset($disabled) ? 'disabled' : '' }}></td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

@endif
