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

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('sidebarSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        // LOOP MENU
        document.querySelectorAll('.sidebar-menu-item').forEach(menu => {
            const menuText = menu.innerText.toLowerCase();
            const submenus = menu.querySelectorAll('.sidebar-submenu-item');

            let submenuMatch = false;

            submenus.forEach(sub => {
                const match = sub.innerText.toLowerCase().includes(keyword);
                sub.style.display = match || keyword === '' ? '' : 'none';
                if (match) submenuMatch = true;
            });

            const menuMatch = menuText.includes(keyword);

            // tampil / sembunyi menu
            if (menuMatch || submenuMatch || keyword === '') {
                menu.style.display = '';
            } else {
                menu.style.display = 'none';
            }

            // auto buka collapse
            const collapse = menu.querySelector('.collapse');
            if (collapse) {
                if (submenuMatch && keyword !== '') {
                    collapse.classList.add('show');
                } else if (keyword === '') {
                    collapse.classList.remove('show');
                }
            }
        });

        document.querySelectorAll('.sidebar-header').forEach(header => {
    let next = header.nextElementSibling;
    let visible = false;

    while (next && !next.classList.contains('sidebar-header')) {
        if (
            next.classList.contains('sidebar-menu-item') &&
            next.style.display !== 'none'
        ) {
            visible = true;
            break;
        }
        next = next.nextElementSibling;
    }

    header.style.display = visible || keyword === '' ? '' : 'none';

    // 🔥 HR sebelum header ikut disembunyikan
    const prev = header.previousElementSibling;
    if (prev && prev.classList.contains('sidebar-divider-item')) {
        prev.style.display = visible || keyword === '' ? '' : 'none';
    }
});
    });
});
</script>

</body>

</html>
