var Encore = require('@symfony/webpack-encore');
var WebpackNotifierPlugin = require('webpack-notifier');

Encore
    // Output-pad waar de gecompresste en samengevoegde assets worden opgeslagen
    .setOutputPath('public/build/')

    // Zet het publieke 'bereikbare' pad voor de webserver om de assets hierboven aan te leveren.
    .setPublicPath('/build')

    // Voegt directory's en assets toe die door de compiler moeten
    .addEntry('app', './assets/js/app.js')

    // Voegt Sourcemaps toe zodat in de browser zodat het geminifyde bestand goed uitgelezen kan worden door Developer Tools
    .enableSourceMaps(!Encore.isProduction())

    // Voegt Jquery toe in de globale-namespace
    .autoProvidejQuery()

    // Maakt het pre-processen van .scss en .sass bestanden beschikbaar.
    .enableSassLoader()

    // Haalt redunante code weg en versimpelt js/css bestanden
    .cleanupOutputBeforeBuild()

    // Native desktop-notificaties wanneer een compilatie gefaald is (voor tijdens development)
    // .enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
