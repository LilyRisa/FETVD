<div class="row page-titles">
    <div class="col-md-3 align-self-center">
        <h4 class="text-themecolor">{{ $name ?? '' }}</h4>
        <select name="" id="" class="form-control">
            <option value="">121</option>
        </select>
    </div>
    {{-- <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                @if(!isset($name) || $tree == 1)
                    <li class="breadcrumb-item active">Home</li>
                @else
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">{{$name}}</li>
                @endif
                
            </ol>
        </div>
    </div> --}}
</div>