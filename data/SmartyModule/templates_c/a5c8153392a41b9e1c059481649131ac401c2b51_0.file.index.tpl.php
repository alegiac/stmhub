<?php /* Smarty version 3.1.27, created on 2015-07-23 22:58:31
         compiled from "/Users/Alessandro/Developer/Web/Php/Clienti/STM/SmileToMove/stmhub/module/Application/view/application/exam/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:92584773455b15577755885_78624862%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a5c8153392a41b9e1c059481649131ac401c2b51' => 
    array (
      0 => '/Users/Alessandro/Developer/Web/Php/Clienti/STM/SmileToMove/stmhub/module/Application/view/application/exam/index.tpl',
      1 => 1437512100,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92584773455b15577755885_78624862',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55b155777a27f1_47806540',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55b155777a27f1_47806540')) {
function content_55b155777a27f1_47806540 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '92584773455b15577755885_78624862';
?>
<div class="jumbotron">
    <h1><?php echo '<?php ';?>echo sprintf($this->translate('Welcome to %sZend Framework 2%s'), '<span class="zf-green">', '</span>') <?php echo '?>';?></h1>
    <p><?php echo '<?php ';?>echo sprintf($this->translate('Congratulations! You have successfully installed the %sZF2 Skeleton Application%s. You are currently running Zend Framework version %s. This skeleton can serve as a simple starting point for you to begin building your application on ZF2.'), '<a href="https://github.com/zendframework/ZendSkeletonApplication" target="_blank">', '</a>', \Zend\Version\Version::VERSION) <?php echo '?>';?></p>
    <p><a class="btn btn-success btn-lg" href="https://github.com/zendframework/zf2" target="_blank"><?php echo '<?php ';?>echo $this->translate('Fork Zend Framework 2 on GitHub') <?php echo '?>';?> &raquo;</a></p>
</div>

<div class="row">

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo '<?php ';?>echo $this->translate('Follow Development') <?php echo '?>';?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo '<?php ';?>echo sprintf($this->translate('Zend Framework 2 is under active development. If you are interested in following the development of ZF2, there is a special ZF2 portal on the official Zend Framework website which provides links to the ZF2 %swiki%s, %sdev blog%s, %sissue tracker%s, and much more. This is a great resource for staying up to date with the latest developments!'), '<a href="http://framework.zend.com/wiki/display/ZFDEV2/Home">', '</a>', '<a href="http://framework.zend.com/zf2/blog">', '</a>', '<a href="https://github.com/zendframework/zf2/issues">', '</a>') <?php echo '?>';?></p>
                <p><a class="btn btn-success pull-right" href="http://framework.zend.com/zf2" target="_blank"><?php echo '<?php ';?>echo $this->translate('ZF2 Development Portal') <?php echo '?>';?> &raquo;</a></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo '<?php ';?>echo $this->translate('Discover Modules') <?php echo '?>';?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo '<?php ';?>echo sprintf($this->translate('The community is working on developing a community site to serve as a repository and gallery for ZF2 modules. The project is available %son GitHub%s. The site is currently live and currently contains a list of some of the modules already available for ZF2.'), '<a href="https://github.com/zendframework/modules.zendframework.com">', '</a>') <?php echo '?>';?></p>
                <p><a class="btn btn-success pull-right" href="http://modules.zendframework.com/" target="_blank"><?php echo '<?php ';?>echo $this->translate('Explore ZF2 Modules') <?php echo '?>';?> &raquo;</a></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo '<?php ';?>echo $this->translate('Help &amp; Support') <?php echo '?>';?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo '<?php ';?>echo sprintf($this->translate('If you need any help or support while developing with ZF2, you may reach us via IRC: %s#zftalk on Freenode%s. We\'d love to hear any questions or feedback you may have regarding the beta releases. Alternatively, you may subscribe and post questions to the %smailing lists%s.'), '<a href="irc://irc.freenode.net/zftalk">', '</a>', '<a href="http://framework.zend.com/wiki/display/ZFDEV/Mailing+Lists">', '</a>') <?php echo '?>';?></p>
                <p><a class="btn btn-success pull-right" href="http://webchat.freenode.net?channels=zftalk" target="_blank"><?php echo '<?php ';?>echo $this->translate('Ping us on IRC') <?php echo '?>';?> &raquo;</a></p>
            </div>
        </div>
    </div>
</div>
<?php }
}
?>