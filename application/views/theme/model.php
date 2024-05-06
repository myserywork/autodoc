<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js sidebar-large lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js sidebar-large lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js sidebar-large lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js sidebar-large">
<!--<![endif]-->
<html lang="en">

<head>
    <!-- BEGIN META SECTION -->
    <meta charset="utf-8">
    <title>AutoDoc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="AutoDoc" name="description" />
    <meta content="Coopera" name="author" />
    <link rel="shortcut icon" href="<?= base_url(); ?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= base_url(); ?>/favicon.ico" type="image/x-icon">
    <!-- END META SECTION -->

    <!-- BEGIN MANDATORY STYLE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="<?= base_url(); ?>assets/css/plugins.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/jquery.jnotify.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/sweetalert.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/jnotify/jNotify.jquery.css">
    <!-- END  MANDATORY STYLE -->

    <!-- BEGIN PAGE LEVEL STYLE -->
    <?php if (is_array($css_to_load)) : ?>
        <?php foreach ($css_to_load as $css) : ?>
            <link rel="stylesheet" href="<?= base_url(); ?>assets/<?= $css; ?>?<?= $version; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- END PAGE LEVEL STYLE -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>

<body data-page="header">
    <input type="hidden" id="base_url" value="<?= base_url(); ?>" />
    <!-- BEGIN WRAPPER -->
    <div id="wrapper">
        <!-- BEGIN MAIN SIDEBAR -->
        <nav id="sidebar">
            <div class="d-flex flex-column flex-shrink-0 p-3 mt-4">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="130" height="40" viewBox="0 0 104 32" fill="none">
                        <path d="M35.303 13.9898C35.303 13.2011 35.9424 12.5618 36.7311 12.5618H37.3064V9.70558C37.3064 8.76362 38.07 8 39.0119 8C39.9539 8 40.7175 8.76362 40.7175 9.70559V12.5618H41.9155C42.7042 12.5618 43.3436 13.2011 43.3436 13.9898C43.3436 14.7786 42.7042 15.4179 41.9155 15.4179H40.7175V19.4382C40.7175 19.9707 40.8033 20.3858 40.9747 20.6836C41.1462 20.9724 41.4169 21.1168 41.7869 21.1168C42.0667 21.1168 42.2968 21.0626 42.4773 20.9543C42.6363 20.851 42.8985 20.9055 42.9826 21.0755L43.7539 22.6354C43.9132 22.9575 43.8196 23.3098 43.4925 23.4585C43.2037 23.5939 42.8292 23.7157 42.369 23.824C41.9178 23.9413 41.4079 24 40.8394 24C39.8196 24 38.9758 23.7067 38.3081 23.1201C37.6403 22.5245 37.3064 21.6041 37.3064 20.3587V15.4179H36.7311C35.9424 15.4179 35.303 14.7786 35.303 13.9898Z" fill="#1677FF" />
                        <path d="M19.0931 23.6752C18.1623 23.6752 17.4078 22.9207 17.4078 21.9899V21.7395C17.3266 21.938 17.119 22.2178 16.7851 22.5787C16.4512 22.9397 16 23.2691 15.4315 23.5669C14.872 23.8557 14.2132 24.0001 13.4552 24.0001C12.3903 24.0001 11.4473 23.7474 10.6261 23.242C9.80485 22.7276 9.15962 22.0283 8.69036 21.1439C8.23012 20.2505 8 19.2443 8 18.1253C8 17.0063 8.23012 16.0046 8.69036 15.1202C9.15962 14.2268 9.80485 13.5229 10.6261 13.0085C11.4473 12.4941 12.3903 12.2369 13.4552 12.2369C14.1951 12.2369 14.8359 12.3633 15.3773 12.616C15.9278 12.8596 16.37 13.1484 16.7039 13.4823C17.0468 13.8072 17.2679 14.1004 17.3672 14.3621V14.2674C17.3672 13.3254 18.1308 12.5618 19.0728 12.5618C20.0147 12.5618 20.7783 13.3254 20.7783 14.2674V21.9899C20.7783 22.9207 20.0238 23.6752 19.0931 23.6752ZM11.3706 18.1253C11.3706 18.7479 11.5104 19.2984 11.7902 19.7767C12.0699 20.246 12.4399 20.6114 12.9002 20.8731C13.3604 21.1349 13.8613 21.2657 14.4027 21.2657C14.9712 21.2657 15.4766 21.1349 15.9188 20.8731C16.361 20.6024 16.7084 20.2324 16.9611 19.7632C17.2228 19.2849 17.3536 18.7389 17.3536 18.1253C17.3536 17.5116 17.2228 16.9702 16.9611 16.5009C16.7084 16.0226 16.361 15.6481 15.9188 15.3774C15.4766 15.1067 14.9712 14.9713 14.4027 14.9713C13.8613 14.9713 13.3604 15.1067 12.9002 15.3774C12.4399 15.6391 12.0699 16.0091 11.7902 16.4874C11.5104 16.9566 11.3706 17.5026 11.3706 18.1253Z" fill="#1677FF" />
                        <path d="M25.5162 17.8816C25.5162 18.8742 25.7147 19.6684 26.1118 20.264C26.5088 20.8505 27.1496 21.1438 28.0339 21.1438C28.9273 21.1438 29.5726 20.8505 29.9696 20.264C30.3667 19.6684 30.5652 18.8742 30.5652 17.8816V14.2538C30.5652 13.3193 31.3228 12.5618 32.2573 12.5618C33.1918 12.5618 33.9493 13.3193 33.9493 14.2538V18.2741C33.9493 19.4202 33.7192 20.4264 33.259 21.2927C32.7987 22.15 32.1264 22.8178 31.2421 23.2961C30.3667 23.7654 29.2973 24 28.0339 24C26.7796 24 25.7102 23.7654 24.8258 23.2961C23.9505 22.8178 23.2827 22.15 22.8224 21.2927C22.3622 20.4264 22.1321 19.4202 22.1321 18.2741V14.2538C22.1321 13.3193 22.8896 12.5618 23.8241 12.5618C24.7586 12.5618 25.5162 13.3193 25.5162 14.2538V17.8816Z" fill="#1677FF" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.3924 23.2556C49.3399 23.7519 50.4228 24.0001 51.6411 24.0001C52.8594 24.0001 53.9333 23.7519 54.8628 23.2556C55.7923 22.7502 56.5187 22.0598 57.0421 21.1845C57.5655 20.3001 57.8272 19.2894 57.8272 18.1523C57.8272 17.0153 57.5655 16.0046 57.0421 15.1202C56.5187 14.2268 55.7923 13.5229 54.8628 13.0085C53.9333 12.4941 52.8594 12.2369 51.6411 12.2369C50.4228 12.2369 49.3399 12.4941 48.3924 13.0085C47.4539 13.5229 46.7139 14.2268 46.1724 15.1202C45.64 16.0046 45.3738 17.0153 45.3738 18.1523C45.3738 19.2894 45.64 20.3001 46.1724 21.1845C46.7139 22.0598 47.4539 22.7502 48.3924 23.2556ZM51.6006 20.745C53.1476 20.745 54.4018 19.5691 54.4018 18.1185C54.4018 16.6679 53.1476 15.4919 51.6006 15.4919C50.0536 15.4919 48.7994 16.6679 48.7994 18.1185C48.7994 19.5691 50.0536 20.745 51.6006 20.745Z" fill="#1677FF" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M76.5381 23.2443C77.4857 23.7407 78.5686 23.9888 79.7869 23.9888C81.0051 23.9888 82.079 23.7407 83.0085 23.2443C83.938 22.739 84.6645 22.0486 85.1879 21.1733C85.7113 20.2889 85.973 19.2782 85.973 18.1411C85.973 17.004 85.7113 15.9933 85.1879 15.109C84.6645 14.2156 83.938 13.5117 83.0085 12.9973C82.079 12.4829 81.0051 12.2257 79.7869 12.2257C78.5686 12.2257 77.4857 12.4829 76.5381 12.9973C75.5996 13.5117 74.8596 14.2156 74.3182 15.109C73.7857 15.9933 73.5195 17.004 73.5195 18.1411C73.5195 19.2782 73.7857 20.2889 74.3182 21.1733C74.8596 22.0486 75.5996 22.739 76.5381 23.2443ZM79.7463 20.7338C81.2934 20.7338 82.5475 19.5579 82.5475 18.1073C82.5475 16.6567 81.2934 15.4807 79.7463 15.4807C78.1993 15.4807 76.9452 16.6567 76.9452 18.1073C76.9452 19.5579 78.1993 20.7338 79.7463 20.7338Z" fill="#1677FF" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M95.0562 20.4964C94.7223 20.6679 94.2981 20.7536 93.7838 20.7536C93.3416 20.7536 92.9265 20.6408 92.5384 20.4152C92.5054 20.3956 92.473 20.3753 92.4411 20.3543C91.6216 19.896 91.0728 19.0566 91.0728 18.097C91.0728 17.0319 91.7488 16.115 92.7204 15.7027C93.0555 15.5415 93.41 15.4609 93.7838 15.4609C94.0899 15.4609 94.3603 15.4934 94.595 15.5582C94.9366 15.6433 95.2525 15.7874 95.5297 15.9781C95.6901 16.0058 95.8627 15.9487 95.9566 15.8112L97.1307 14.0914C97.4145 13.6756 97.3248 13.1321 96.87 12.9161C96.5091 12.7356 96.0624 12.5777 95.5299 12.4423C95.0065 12.2979 94.4109 12.2257 93.7431 12.2257C92.4707 12.2257 91.3517 12.4694 90.3861 12.9567C89.4205 13.435 88.667 14.1118 88.1256 14.9871C87.5931 15.8625 87.3269 16.9003 87.3269 18.1005C87.3269 19.2917 87.5931 20.3295 88.1256 21.2139C88.667 22.0982 89.4205 22.7841 90.3861 23.2714C91.3517 23.7497 92.4707 23.9888 93.7431 23.9888C94.42 23.9888 95.0201 23.9211 95.5435 23.7858C96.0759 23.6504 96.5181 23.4925 96.87 23.312C97.3068 23.0768 97.4019 22.5365 97.1244 22.1253L96.1834 20.7307C95.9465 20.3797 95.4287 20.2951 95.0562 20.4964Z" fill="#1677FF" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M62.4017 23.2443C63.3492 23.7407 64.4321 23.9888 65.6504 23.9888C66.8687 23.9888 67.9426 23.7407 68.8721 23.2443C69.8016 22.739 70.528 22.0486 71.0514 21.1733C71.5748 20.2889 71.8365 19.2782 71.8365 18.1411C71.8365 17.004 71.5748 15.9933 71.0514 15.109C70.528 14.2156 69.8016 13.5117 68.8721 12.9973C67.9426 12.4829 66.8687 12.2257 65.6504 12.2257C64.4321 12.2257 63.3492 12.4829 62.4017 12.9973C61.4631 13.5117 60.7232 14.2156 60.1817 15.109C59.6493 15.9933 59.3831 17.004 59.3831 18.1411C59.3831 19.2782 59.6493 20.2889 60.1817 21.1733C60.7232 22.0486 61.4631 22.739 62.4017 23.2443ZM65.6097 20.7338C67.1567 20.7338 68.4109 19.5579 68.4109 18.1073C68.4109 16.6567 67.1567 15.4807 65.6097 15.4807C64.0627 15.4807 62.8085 16.6567 62.8085 18.1073C62.8085 19.5579 64.0627 20.7338 65.6097 20.7338Z" fill="#1677FF" />
                        <path d="M68.7876 9.71447C68.7876 8.78148 69.5439 8.02515 70.4769 8.02515C71.4099 8.02515 72.1662 8.78148 72.1662 9.71447V22.3106C72.1662 23.2436 71.4099 24 70.4769 24C69.5439 24 68.7876 23.2436 68.7876 22.3106V9.71447Z" fill="#1677FF" />
                    </svg>
                </a>
                <ul class="nav nav-pills flex-column mb-auto mt-5">
                    <li class="nav-item">
                        <a href="<?=base_url();?>" class="nav-link <?=(($title == "Tela de Início" ? "active" : "link-dark"));?>" aria-current="page">
                            <img src="<?= base_url(); ?>/assets/img/icons/sidebar-home.svg" />
                            Tela de Início
                        </a>
                    </li>
                    <li>
                        <a href="<?=base_url('convenios');?>" class="nav-link <?=(($title == "Convênios" ? "active" : "link-dark"));?>">
                            <img src="<?= base_url(); ?>/assets/img/icons/sidebar-heart.svg" />
                            Convênios
                        </a>
                    </li>
                    <li>
                        <a href="<?=base_url('documentos');?>" class="nav-link <?=(($title == "Documentos" ? "active" : "link-dark"));?>">
                            <img src="<?= base_url(); ?>/assets/img/icons/sidebar-doc.svg" />
                            Documentos
                        </a>
                    </li>
                    <?php if ($this->ion_auth->is_admin()): ?>
                        <li>
                            <a href="<?=base_url('auth');?>" class="nav-link <?=(($title == "Configurações" ? "active" : "link-dark"));?>">
                                <img src="<?= base_url(); ?>/assets/img/icons/sidebar-config.svg" />
                                Configurações
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <!-- END MAIN SIDEBAR -->

        <!-- BEGIN MAIN CONTENT -->
        <div id="main-content" class="main-content">
            <?php if ($this->session->flashdata('error') != null) { ?>
                <div id="notification" style="display: none;" data-type="error" class="notification" data-message="<i class='fa fa-frown-o' style='padding-right:6px'></i> <?php echo $this->session->flashdata('error'); ?>" data-horiz-pos="right" data-verti-pos="bottom"></div>
            <?php } ?>
            <?php if ($this->session->flashdata('success') != null) { ?>
                <div id="notification" style="display: none;" data-type="success" class="notification" data-message="<i class='fa fa-check-square-o' style='padding-right:6px'></i> <?php echo $this->session->flashdata('success'); ?>" data-horiz-pos="right" data-verti-pos="bottom"></div>
            <?php } ?>
            <?php if ($this->session->flashdata('info') != null) { ?>
                <div id="notification" style="display: none;" data-type="info" class="notification" data-message="<i class='fa fa-info-circle' style='padding-right:6px'></i> <?php echo $this->session->flashdata('info'); ?>" data-horiz-pos="right" data-verti-pos="bottom"></div>
            <?php } ?>
            <?php
            if (isset($view)) {
                $this->load->view($view);
            }
            ?>
            <div class="md-overlay"></div>
            <!-- the overlay element -->
        </div>
        <!-- END MAIN CONTENT -->
    </div>
    <!-- END WRAPPER -->
    <!-- BEGIN MANDATORY SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js" integrity="sha256-UnTxHm+zKuDPLfufgEMnKGXDl6fEIjtM+n1Q6lL73ok=" crossorigin="anonymous"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.jnotify.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.blockUI.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/sweetalert.min.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/notifications.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/plugins/jnotify/jNotify.jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!--cript src="<?= base_url(); ?>assets/js/main.js" type="text/javascript"></script-->
    <!-- END MANDATORY SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <?php if (is_array($js_to_load)) : ?>
        <?php foreach ($js_to_load as $js) : ?>
            <script src="<?= base_url(); ?>assets/<?= $js; ?>?<?= $version; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- END  PAGE LEVEL SCRIPTS -->
    <!--script src="<?= base_url(); ?>assets/js/application.js?<?= $version; ?>"></script-->
</body>

</html>