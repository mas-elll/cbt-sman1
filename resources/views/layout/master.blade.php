<!DOCTYPE html>
<html lang="en">
@include('layout.head')
@include('sweetalert::alert')
<body id="page-top">
    <div id="wrapper">
        @include('layout.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('layout.header')
                @include('layout.content')
            </div>
            @include('layout.footer')
        </div>
    </div>
    @include('layout.script')
</body>
</html>