<label for="name">Schedule:</label>
<select class="form-control select2" name="schedule_id" id="schedule">
    <option disabled selected></option>
    @foreach($schedules as $schedule)
        <option value="{{ $schedule->id }}">{{ $schedule->title }}</option>
    @endforeach
</select>
