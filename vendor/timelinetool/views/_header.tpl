<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>{if isset($title)}{$title} - {/if}{$meta.title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{$meta.description}" />
    <meta name="author" content="{$meta.author}" />

    <!-- Le styles -->
    <link href="{$path.css}/bootstrap.css" rel="stylesheet" />
    <link href="{$path.css}/bootstrap-responsive.css" rel="stylesheet" />

    <script src="{$path.js}/jquery.min.js"></script>
    <script src="{$path.js}/bootstrap.min.js"></script>
    <script src="{$path.js}/handlebars.js"></script>
    <script src="{$path.js}/handlebars.helper.js"></script>
    <script src="{$path.js}/sprintf.js"></script>
    <script type="text/javascript">
      var meta = {$meta_json};
      var path = {$path_json};
    </script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{$path.img}/ico/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$path.img}/ico/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$path.img}/ico/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$path.img}/ico/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="{$path.img}/ico/apple-touch-icon-57-precomposed.png" />
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
  </head>

  <body>

{include file='_asset.modal.tpl'}
{include file='_login.form.template.tpl'}

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="{$meta.url}{if isset($hash)}/{$hash}{/if}">
            {if isset($title)}{$title}{else}{$meta.title}{/if}</a>
          {if $user.authenticated}
            <div class="btn-group pull-right">
              <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-user"></i> {$user.name}
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                {if isset($timeline.hash)}
                  <li><a href="{$meta.url}/admin/{$timeline.hash}.html"
                        title="{$lang.admin.timeline.update.alt}">
                    <i class="icon-wrench"></i> {$lang.admin.timeline.update.label}
                  </a></li>
                {/if}
                {if $user.has_admin_right || $user.editable_timelines}
                  <li><a href="{$meta.url}/admin.html"
                        title="{$lang.navigation.admin.alt}">
                    <i class="icon-th"></i> {$lang.navigation.admin.label}
                  </a></li>
                {/if}
                {if !$user.has_admin_right && $user.editable_timelines}
                  <li class="divider-horizontal"></li>
                  {foreach $user.editable_timelines as $user_timeline}
                    <li><a href="{$meta.url}/admin/{$user_timeline}.html">
                      <i class="icon-th-large"></i> {$user_timeline}</a></li>
                  {/foreach}
                {/if}
                <li class="divider-horizontal"></li>
                <li>
                  <a id="js-logout-button" 
                      href="#logout"
                      title="{$lang.navigation.logout.alt}">
                    <i class="icon-off"></i> {$lang.navigation.logout.label}
                  </a>
                </li>
              </ul>
            </div>
          {else}
            <ul class="nav pull-right">
              <li>
                <a id="js-login-button" 
                    href="#login"
                    title="{$lang.navigation.login.alt}">
                  <i class="icon-user icon-white"></i> {$lang.global.login.label}
                </a>
              </li>
            </ul>
          {/if}
          <div class="nav-collapse">
            <ul class="nav">
              {if isset($navlist)}
                {foreach $navlist as $navkey => $navitem}
                  <li><a 
                      href="{if isset($navitem.url)}{$navitem.url}{else}#{$navkey}{/if}" 
                      id="nav-{$navkey}" 
                      {if isset($navitem.alt)}title="{$navitem.alt}"{/if}
                    >
                    {if isset($navitem.icon)}
                      <i class="icon-{$navitem.icon} icon-white"></i>
                    {/if}
                    {if isset($navitem.label)}{$navitem.label}{/if}</a></li>
                  {if !isset($navitem.nodivider)}<li class="divider-vertical"></li>{/if}
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

