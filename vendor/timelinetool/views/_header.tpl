<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>{if $title}{$title} - {/if}{$meta.title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$meta.description}">
    <meta name="author" content="{$meta.author}">

    <!-- Le styles -->
    <link href="{$path.css}/bootstrap.css" rel="stylesheet">
    <link href="{$path.css}/bootstrap-responsive.css" rel="stylesheet">

    <script src="{$path.js}/jquery.js"></script>
    <script src="{$path.js}/bootstrap.js"></script>
    <script src="{$path.js}/handlebars.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{$path.root}/public/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$path.root}/public/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$path.root}/public/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$path.root}/public/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="{$path.root}/public/ico/apple-touch-icon-57-precomposed.png">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="{if $hash}/{$hash}{else}{$meta.url}{/if}">
            {if $title}{$title}{else}{$meta.title}{/if}</a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> {$user.name}
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              {if $timeline.hash}<li><a href="/admin/{$timeline.hash}.html">
                <i class="icon-wrench"></i> {$lang.timeline.update}</a></li>{/if}
              <li><a href="/admin.html">
                <i class="icon-th"></i> {$lang.navigation.admin}</a></li>
              <li><a href="#logout">
                <i class="icon-off"></i> {$lang.global.logout}</a></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              {if $navlist}
                {foreach $navlist as $navkey => $navitem}
                  <li><a href="#{$navkey}" id="nav-{$navkey}">
                    {if $navitem.icon}<i class="icon-{$navitem.icon} icon-white"></i>{/if}
                    {if $navitem.label}{$navitem.label}{/if}</a></li>
                  {if !$navitem.nodivider}<li class="divider-vertical"></li>{/if}
                {/foreach}
              {/if}
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    
    <div class="container">
      {if isset($_FLASH.message) && !empty($_FLASH.message)}
        <div id='js-flash_message'>
          <div class='alert alert-{$_FLASH.type}' id='js-flash_{$_FLASH.type}'>
            <a class='close' href='#'>×</a>
            {if isset($_FLASH.headline) && !empty($_FLASH.headline)}<h4 class='alert-heading'>{$_FLASH.headline}</h4>{/if}
            <p>
              {$_FLASH.message}
            </p>
          </div>
        </div>
      {/if}

