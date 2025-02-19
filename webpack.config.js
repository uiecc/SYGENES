const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .enablePostCssLoader()

    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .enableStimulusPlugin()
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)

    .enableSingleRuntimeChunk()
    .enableStimulusBridge('./assets/controllers.json')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureResolve({
        alias: {
            '@symfony/stimulus-bridge': path.resolve(__dirname, 'node_modules/@symfony/stimulus-bridge'),
        },
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })
;

module.exports = Encore.getWebpackConfig();