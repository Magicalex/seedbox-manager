<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Seedbox Manager</title>
        <link href="./components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link type="text/css" rel="stylesheet" href="./components/components-font-awesome/css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="./themes/{{ user.theme }}/css/style.css">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1">
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>

    <header class="navbar navbar-default navbar-static-top">
        {% include './template/header.twig.php' %}
    </header>

    {% if get.option is defined %}

    <!-- CONTENEUR -->
    <div class="container marg">
        <h1 class="page-header dashboard"><i class="glyphicon glyphicon-wrench"></i> Réglage de votre utilisateur</h1>
        {% include './template/notif-option.twig.php' %}
        <section class="row">
            {% include './option.twig.php' %}
        </section>
    </div>

    {% elseif get.admin is defined and user.is_owner == true %}

    <!-- CONTENEUR -->
    <div class="container marg">
        <h1 class="page-header dashboard"><i class="glyphicon glyphicon-cog"></i> Espace administration</h1>
        {% include './template/notif-admin.twig.php' %}
        <section class="row">
            {% include './administration.twig.php' %}
        </section>
    </div>

    {% else %}

    <!-- CONTENEUR -->
    <div class="container marg">
        <h1 class="page-header dashboard"><i class="glyphicon glyphicon-dashboard"></i> Tableau de bord</h1>
        <noscript>
            <div class="alert alert-info">
                <h4>Information</h4>
                Javascript n'est pas activé ou quelque chose empêche sa bonne exécution sur votre navigateur web.<br>
                Pour pouvoir utiliser cette interface l'activation de javascript est nécessaire.
            </div>
        </noscript>

         {% include './template/notif-index.twig.php' %}

        <section class="row">
            {% if user.blocInfo == true %}
                {% include './template/bloc-info.twig.php' %}
            {% endif %}

            {% if user.blocFtp == true %}
                {% include './template/bloc-ftp.twig.php' %}
            {% endif %}
        </section>
        <section class="row">
            {% if user.blocRtorrent == true %}
                {% include './template/bloc-rtorrent.twig.php' %}
            {% endif %}
        </section>

    </div>

    {% endif %} {# if $_GET page #}

{% include './template/modal.twig.php' %}
{% include './template/footer.twig.php' %}
