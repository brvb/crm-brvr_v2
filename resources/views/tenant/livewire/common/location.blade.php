<div class="col-12">
    <div class="row">
        <section class="col-xs-12 col-md-6">
            <label>{{ __('District') }}</label>
            <input type="text" name="district" id="district" @isset($district) @if($district != "") value="{{$district}}" @endif @endisset class="form-control">
            {{-- <select type="text" name="district" id="district" class="form-control" wire:change="$emit('updatedDistrict', $event.target.value)">
                <option value="">{{ __('Select district') }}</option>
                @foreach($districts as $value)
                    <option value="{{ $value->id }}" @if($district==$value->id) selected @endif>{{ $value->name }}</option>
                @endforeach
            </select> --}}
        </section>
        <section class="col-xs-12 col-md-6">
            <label>{{ __('City') }}</label>
            <input type="text" name="county" id="county" @isset($county) @if($county != "") value="{{$county}}" @endif @endisset class="form-control">
            {{-- <select type="text" name="county" id="county" class="form-control">
                <option value="" @if(!$county) selected @endif>{{ __('Select city') }}</option>
                @if(isset($counties))
                    @foreach($counties as $value)
                        <option value="{{ $value->id }}" @if($county==$value->id && $district==$value->district_id) selected @endif>{{ $value->name }}</option>
                    @endforeach
                @endif
            </select> --}}
        </section>
    </div>
</div>
