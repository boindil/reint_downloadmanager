<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

(static function ($extKey = 'reint_downloadmanager', $iconIdentifier = 'reint-dm-icon') {
    /***************
     * Make the extension configuration accessible
     */
    $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    );
    $dmManagerPackageConfiguration = $extensionConfiguration->get($extKey);

    $extensionName = 'RENOLIT.' . $extKey;
    if (isset($dmManagerPackageConfiguration['disableDefaultPlugin']) && !(bool)$dmManagerPackageConfiguration['disableDefaultPlugin']) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionName,
            'Reintdlm',
            [
                \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'list, topdownloads, empty, filesearch, download',
            ],
            [
                \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'download',
            ],
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TsConfig/Plugin.tsconfig">');
    }
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extensionName,
        'DmList',
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'list, download',
        ],
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'download',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extensionName,
        'DmTopdownloads',
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'topdownloads, download',
        ],
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'download',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extensionName,
        'DmFilesearch',
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'filesearch, download',
        ],
        [
            \RENOLIT\ReintDownloadmanager\Controller\ManagerController::class => 'download',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/cache/frontend/class.t3lib_cache_frontend_variablefrontend.php']['set'][$extKey] =
        \RENOLIT\ReintDownloadmanager\Hooks\SetPageCacheHook::class . '->set';

    /***************
     * Register Icons
     */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        $iconIdentifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:' . $extKey . '/ext_icon.svg']
    );

    /* add a default pageTS if allowed in extension configuration */
    if (isset($dmManagerPackageConfiguration['disableDefaultPageTs']) && !(bool)$dmManagerPackageConfiguration['disableDefaultPageTs']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TsConfig/Default.tsconfig">');
    }

    /* add migration wizard for FlexForms */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['reintDownloadmanager_migrateFlexformWizard'] = \RENOLIT\ReintDownloadmanager\Updates\MigrateFlexformWizard::class;

})();
