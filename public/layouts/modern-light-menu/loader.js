window.addEventListener("load", function(){

    // Remove Loader
    var load_screen = document.getElementById("load_screen");
    document.body.removeChild(load_screen);

    var layoutName = 'Modern Light Menu';

    var settingsObject = {
        admin: 'Cork Admin Template',
        settings: {
            layout: {
                name: layoutName,
                toggle: true,
                darkMode: false,
                boxed: true,
                
            }
        },
        reset: false
    }


    if (settingsObject.reset) {
        localStorage.clear()
    }

    if (localStorage.length === 0) {
        corkThemeObject = settingsObject;
    } else {
        try {
            getcorkThemeObject = localStorage.getItem("theme");
            if (getcorkThemeObject && getcorkThemeObject !== 'undefined' && getcorkThemeObject !== 'null') {
                getParseObject = JSON.parse(getcorkThemeObject);
                ParsedObject = getParseObject;

                if (getcorkThemeObject !== null) {
                    if (ParsedObject && ParsedObject.admin === 'Cork Admin Template') {
                        if (ParsedObject.settings && ParsedObject.settings.layout && ParsedObject.settings.layout.name === layoutName) {
                            corkThemeObject = ParsedObject;
                        } else {
                            corkThemeObject = settingsObject;
                        }
                    } else {
                        if (!ParsedObject || ParsedObject.admin === undefined) {
                            corkThemeObject = settingsObject;
                        }
                    }
                } else {
                    corkThemeObject = settingsObject;
                }
            } else {
                corkThemeObject = settingsObject;
            }
        } catch (error) {
            console.warn('localStorage parsing error:', error);
            // Clear invalid localStorage data and use default settings
            localStorage.removeItem("theme");
            corkThemeObject = settingsObject;
        }
    }

    // Get Dark Mode Information i.e darkMode: true or false
    
    try {
        if (corkThemeObject && corkThemeObject.settings && corkThemeObject.settings.layout && corkThemeObject.settings.layout.darkMode) {
            localStorage.setItem("theme", JSON.stringify(corkThemeObject));
            getcorkThemeObject = localStorage.getItem("theme");
            getParseObject = JSON.parse(getcorkThemeObject)
        
            if (getParseObject && getParseObject.settings && getParseObject.settings.layout && getParseObject.settings.layout.darkMode) {
                ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
                document.body.classList.add('dark');
                if (ifStarterKit) {
                    if (document.querySelector('.navbar-logo')) {
                        document.querySelector('.navbar-logo').setAttribute('src', '{{ asset(\App\Models\Settings::first()->favicon) }}')
                    }
                } else {
                    if (document.querySelector('.navbar-logo') && getParseObject.settings.layout.logo && getParseObject.settings.layout.logo.darkLogo) {
                        document.querySelector('.navbar-logo').setAttribute('src', getParseObject.settings.layout.logo.darkLogo)
                    }
                }
            }
        } else {
            localStorage.setItem("theme", JSON.stringify(corkThemeObject));
            getcorkThemeObject = localStorage.getItem("theme");
            getParseObject = JSON.parse(getcorkThemeObject)

            if (getParseObject && getParseObject.settings && getParseObject.settings.layout && !getParseObject.settings.layout.darkMode) {
                ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
                document.body.classList.remove('dark');
                if (ifStarterKit) {
                    if (document.querySelector('.navbar-logo')) {
                        document.querySelector('.navbar-logo').setAttribute('src', '{{ asset(\App\Models\Settings::first()->favicon) }}')
                    }
                } else {
                    if (document.querySelector('.navbar-logo') && getParseObject.settings.layout.logo && getParseObject.settings.layout.logo.lightLogo) {
                        document.querySelector('.navbar-logo').setAttribute('src', getParseObject.settings.layout.logo.lightLogo)
                    }
                }
            }
        }
    } catch (error) {
        console.warn('Dark mode setup error:', error);
        // Fallback: keep current body class or use default
    }

    // Get Layout Information i.e boxed: true or false

    try {
        if (corkThemeObject && corkThemeObject.settings && corkThemeObject.settings.layout && corkThemeObject.settings.layout.boxed) {
            localStorage.setItem("theme", JSON.stringify(corkThemeObject));
            getcorkThemeObject = localStorage.getItem("theme");
            getParseObject = JSON.parse(getcorkThemeObject)
        
            if (getParseObject && getParseObject.settings && getParseObject.settings.layout && getParseObject.settings.layout.boxed) {
                if (document.body.getAttribute('layout') !== 'full-width') {
                    document.body.classList.add('layout-boxed');
                    if (document.querySelector('.header-container')) {
                        document.querySelector('.header-container').classList.add('container-xxl');
                    }
                    if (document.querySelector('.middle-content')) {
                        document.querySelector('.middle-content').classList.add('container-xxl');
                    }
                } else {
                    document.body.classList.remove('layout-boxed');
                    if (document.querySelector('.header-container')) {
                        document.querySelector('.header-container').classList.remove('container-xxl');
                    }
                    if (document.querySelector('.middle-content')) {
                        document.querySelector('.middle-content').classList.remove('container-xxl');
                    }
                }
            }
        } else {
            localStorage.setItem("theme", JSON.stringify(corkThemeObject));
            getcorkThemeObject = localStorage.getItem("theme");
            getParseObject = JSON.parse(getcorkThemeObject)
            
            if (getParseObject && getParseObject.settings && getParseObject.settings.layout && !getParseObject.settings.layout.boxed) {
                if (document.body.getAttribute('layout') !== 'boxed') {
                    document.body.classList.remove('layout-boxed');
                    if (document.querySelector('.header-container')) {
                        document.querySelector('.header-container').classList.remove('container-xxl');
                    }
                    if (document.querySelector('.middle-content')) {
                        document.querySelector('.middle-content').classList.remove('container-xxl');
                    }
                } else {
                    document.body.classList.add('layout-boxed');
                    if (document.querySelector('.header-container')) {
                        document.querySelector('.header-container').classList.add('container-xxl');
                    }
                    if (document.querySelector('.middle-content')) {
                        document.querySelector('.middle-content').classList.add('container-xxl');
                    }
                }
            }
        }
    } catch (error) {
        console.warn('Layout setup error:', error);
        // Fallback: keep current layout or use default
    }

    


    
});

