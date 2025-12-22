<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel - @stack("title_module")</title>

    @include("pages.layouts.css.style-css")

    @stack("css_style")

</head>

<body id="page-top">

    <div id="wrapper">

        @include("pages.layouts.components.sidebar")

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include("pages.layouts.components.navbar")

                <div class="container-fluid">

                    @stack("content_app")

                </div>
            </div>

            @include("pages.layouts.components.footer")

        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include("pages.layouts.components.modal")

    @include("pages.layouts.javascript.style-js")

    @stack("js_style")
</body>

</html>
