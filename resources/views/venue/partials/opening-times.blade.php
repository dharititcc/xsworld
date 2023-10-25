<tr>
    <th>{{$name}}</th>
    <td>
        <input type="hidden" name="res_id" id="res_id" data-id="{{$id}}">
        <input
            class="start_time"
            style="display: none"
            data-day_id="{{$key}}"
            value="{{$key}}"
            name="start_time[{{$key}}]"
            type="time"
            value="{{ isset( $res_time->id ) && $res_time->start_time ? $res_time->start_time : '' }}"
            placeholder="Start Time"
        />
        <input
            class="close_time"
            placeholder="Close TIme"
            style="display: none"
            name="end_time[{{$key}}]"
            type="time"
            value="{{ isset( $res_time->id ) && $res_time->close_time ? $res_time->close_time : '' }}"
        >
        <label for="time" class="times">-</label>
    </td>
</tr>