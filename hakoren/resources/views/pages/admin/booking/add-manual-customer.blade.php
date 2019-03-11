<div>
    <div class="form-group">
        <label class="col-lg-2 control-label">姓</label>
        <div class="col-lg-4"><input name="client_first_name" class="form-control" value="{{ $user->first_name }}" required></div>
        <label class="col-lg-2 control-label">名</label>
        <div class="col-lg-4"><input name="client_last_name" class="form-control" value="{{ $user->last_name }}" required></div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">電話</label>
        <div class="col-lg-4"><input name="client_phone" class="form-control" value="{{ $user->phone }}" required></div>
        <label class="col-lg-2 control-label">メール</label>
        <div class="col-lg-4"><input name="client_email" class="form-control" value="{{ $user->email }}" required></div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">運転手名</label>
        <div class="col-lg-4"><input name="driver_name" class="form-control"></div>
        <label class="col-lg-2 control-label">緊急連絡先</label>
        <div class="col-lg-4"><input name="emergency_phone" class="form-control" required></div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">支払い方法</label>
        <div class="col-lg-4">
            <select name="paymethod" class="form-control">
                @foreach($paymethods as $paymethod)
                    <option value="{{ $paymethod->id }}">{{ $paymethod->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">支払い　ID</label>
        <div class="col-lg-4"><input name="pay_id" class="form-control"></div>
        <label class="col-lg-2 control-label">取引 ID</label>
        <div class="col-lg-4"><input name="trans_id" class="form-control"></div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">payment ID</label>
        <div class="col-lg-4"><input name="client_pay_id" class="form-control"></div>
        <label class="col-lg-2 control-label">transaction ID</label>
        <div class="col-lg-4"><input name="client_trans_id" class="form-control"></div>
    </div>
</div>
