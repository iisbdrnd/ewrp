<select id="account" name="account" data-fv-icon="" class="select2" style="width:100%;">
    <option value=""></option>
    @foreach($payment_accounts_level_four as $levelThreeKey=>$payment_account_level_four)
        @if (isset($payment_accounts[$levelThreeKey]))
        <optgroup label="{{$payment_accounts[$levelThreeKey]->account_code.' - '.$payment_accounts[$levelThreeKey]->account_head}}">
        @foreach($payment_account_level_four as $payment_account_level_four)
          <option value="{{$payment_account_level_four->account_code.'~'.$payment_account_level_four->account_head}}">{{$payment_account_level_four->account_code.' - '.$payment_account_level_four->account_head}}</option>
        @endforeach
        </optgroup>
        @endif
    @endforeach
</select>
