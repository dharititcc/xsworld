<tr>
    <th>{{$name}}</th>
    <td>
        <div class="restaurant-timing"><input type="hidden" name="res_id" id="res_id" data-id="{{$id}}">
            <?php //dd($key); ?>
            <input
                class="start_time form-control invert"
                style="display: none"
                data-day_id="{{$key}}"
                {{-- value="{{$key}}" --}}
                name="start_time[{{$key}}]"
                type="time"
                value="{{ isset( $res_time->id ) && $res_time->start_time ? $res_time->start_time : '' }}"
                placeholder="Start Time"
            />
            <input
                class="close_time form-control invert"
                placeholder="Close TIme"
                style="display: none"
                name="end_time[{{$key}}]"
                type="time"
                value="{{ isset( $res_time->id ) && $res_time->close_time ? $res_time->close_time : '' }}"
            >
            @if(isset($res_time) && $res_time->start_time)
                <label for="time" class="times"> {{$res_time->start_time}} - {{$res_time->close_time}} </label>
            @else 
                <label for="time" class="times"> - </label>
            @endif
        </div>
    </td>
</tr>