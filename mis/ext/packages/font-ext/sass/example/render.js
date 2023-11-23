/*
 * This file is generated by Sencha Cmd and should NOT be edited. It will be replaced
 * during an upgrade.
 */

// This flag is checked by many Components to avoid compatibility warnings when
// the code is running under the slicer
Ext.slicer = true;

Ext.require([
    'Ext.layout.Context'
]);

Ext.theme = Ext.apply(Ext.theme || {}, {
    /**
     * The array of all component manifests. These objects have the following set of
     * properties recognized by the slicer:
     * @private
     */
    _manifest: [],

    /**
     * The collection of shortcuts for a given alias (e.g., 'widget.panel'). This is an
     * object keyed by alias whose values are arrays of shortcut definitions.
     * @private
     */
    _shortcuts: {},

    doRequire: function(xtype) {
        if (xtype.indexOf("widget.") != 0) {
            xtype = "widget." + xtype;
        }

        Ext.require([xtype]);
    },

    /**
     * Adds one ore more component entries to the theme manifest. These entries will be
     * instantiated by the `Ext.theme.render` method when the page is ready.
     *
     * Usage:
     *
     *      Ext.theme.addManifest({
     *              xtype: 'widget.menu',
     *              folder: 'menu',
     *              delegate: '.x-menu-item-link',
     *              filename: 'menu-item-active',
     *              config: {
     *                  floating: false,
     *                  width: 200,
     *                  items: [{
     *                      text: 'test',
     *                      cls: 'x-menu-item-active'
     *                  }]
     *              }
     *          },{
     *              //...
     *          });
     *
     * @param manifest {Object} An object with type of component, slicing information and
     * component configuration. If this parameter is an array, each element is treated as
     * a manifest entry. Otherwise, each argument passed is treated as a manifest entry.
     *
     * @param manifest.xtype {String} The xtype ('grid') or alias ('widget.grid'). This
     * is used to specify the type of component to create as well as a potential key to
     * any `shortcuts` defined for the xtype.
     *
     * @param manifest.config {Object} The component configuration object. The properties
     * of this depend on the `xtype` of the component.
     *
     * @param [manifest.delegate] {String} The DOM query to use to select the element to
     * slice. The default is to slice the primary element of the component.
     *
     * @param [manifest.parentCls] An optional CSS class to add to the parent of the
     * component.
     *
     * @param [manifest.setup] {Function} An optional function to be called to initialize
     * the component.
     * @param manifest.setup.component {Ext.Component} The component instance
     * @param manifest.setup.container {Element} The component's container.
     *
     * @param [manifest.folder] {String} The folder in to which to produce image slices.
     * Only applies to Ext JS 4.1 (removed in 4.2).
     *
     * @param [manifest.filename] {String} The base filename for slices.
     * Only applies to Ext JS 4.1 (removed in 4.2).
     *
     * @param [manifest.reverse] {Boolean} True to position the slices for linear gradient
     * background at then opposite "end" (right or bottom) and apply the stretch to the
     * area before it (left or top). Only applies to Ext JS 4.1 (removed in 4.2).
     */
    addManifest: function(manifest) {
        var all = Ext.theme._manifest,
            add = Ext.isArray(manifest) ? manifest : arguments;

        if (manifest.xtype) {
            Ext.theme.doRequire(manifest.xtype);
        }

        for (var i = 0, n = add.length; i < n; ++i) {
            if (add[i].xtype) {
                Ext.theme.doRequire(add[i].xtype);
            }

            all.push(add[i]);
        }
    },

    /**
     * Adds one or more shortcuts to the rendering process. A `shortcut` is an object that
     * looks the same as a `manifest` entry. These are combined by copying the properties
     * from the shortcut over those of the manifest entry. In basic terms:
     *
     *      var config = Ext.apply(Ext.apply({}, manfiest.config), shortcut.config);
     *      var entry = Ext.apply(Ext.apply({}, manfiest), shortcut);
     *      entry.config = config;
     *
     * This is not exactly the process, but the idea is the same. The difference is that
     * the `ui` of the manifest entry is used to replace any `"{ui}"` substrings found in
     * any string properties of the shortcut or its `config` object.
     *
     * Usage:
     *
     *      Ext.theme.addShortcuts({
     *          'widget.foo': [{
     *                  config: {
     *                  }
     *              },{
     *                  config: {
     *                  }
     *              }],
     *
     *          'widget.bar': [ ... ]
     *      });
     */
    addShortcuts: function(shortcuts) {
        var all = Ext.theme._shortcuts;

        for (var key in shortcuts) {

            var add = shortcuts[key],
                xtype = Ext.theme.addWidget(key),
                existing = all[xtype];

            Ext.theme.doRequire(xtype);

            for (var i = 0; i < add.length; i++) {
                var config = add[i];

                if (config.xtype) {
                    Ext.theme.doRequire(config.xtype);
                }
            }

            if (!existing) {
                all[xtype] = existing = [];
            }

            existing.push.apply(existing, add);
        }
    },

    /**
     * This method ensures that a given string has the specified prefix (e.g., "widget.").
     * @private
     */
    addPrefix: function(prefix, s) {
        if (!s || (s.length > prefix.length && s.substring(0, prefix.length) === prefix)) {
            return s;
        }

        return prefix + s;
    },

    /**
     * This method returns the given string with "widget." added to the front if that is
     * not already present.
     * @private
     */
    addWidget: function(str) {
        return Ext.theme.addPrefix('widget.', str);
    },

    /**
     * This method accepts an manifest entry and a shortcut entry and returns the merged
     * version.
     * @private
     */
    applyShortcut: function(manifestEntry, shortcut) {
        var ui = manifestEntry.ui,
            config = Ext.theme.copyProps({}, manifestEntry.config),
            entry = Ext.theme.copyProps({}, manifestEntry);

        if (ui && !config.ui) {
            config.ui = ui;
        }

        if (shortcut) {
            var tpl = { ui: ui };

            Ext.theme.copyProps(entry, shortcut, tpl);
            Ext.theme.copyProps(config, shortcut.config, tpl);
        }

        entry.xtype = Ext.theme.addWidget(entry.xtype);
        entry.config = config; // both guys have "config" so smash merged one on now...

        return entry;
    },

    /**
     * This method copies property from a `src` object to a `dest` object and reaplces
     * `"{foo}"` fragments of any string properties as defined in the `tpl` object.
     *
     *      var obj = Ext.theme.copyProps({}, {
     *                      foo: 'Hello-{ui}'
     *                  }, {
     *                      ui: 'World'
     *                  });
     *
     *      console.log('obj.foo: ' + obj.foo); // logs "Hello-World"
     *
     * @return {Object} The `dest` object or a new object (if `dest` was null).
     * @private
     */
    copyProps: function(dest, src, tpl) {
        var out = dest || {},
            replacements = [],
            token;

        if (src) {
            if (tpl) {
                for (token in tpl) {
                    replacements.push({
                        re: new RegExp('\\{' + token + '\\}', 'g'),
                        value: tpl[token]
                    });
                }
            }

            for (var key in src) {
                var val = src[key];

                if (tpl && typeof val === 'string') {
                    for (var i = 0; i < replacements.length; ++ i) {
                        val = val.replace(replacements[i].re, replacements[i].value);
                    }
                }

                out[key] = val;
            }
        }

        return out;
    },

    /**
     * Renders a component given its manifest and shortcut entries.
     * @private
     */
    renderWidget: function(manifestEntry, shortcut) {
        var entry = Ext.theme.applyShortcut(manifestEntry, shortcut),
            config = entry.config,
            widget = Ext.create(entry.xtype, config),
            ct = Ext.fly(document.body).createChild({ cls: 'widget-container' });

        Ext.theme.currentWidget = widget;

        if (widget.floating === true) {
            widget.floating = { shadow: false };
        }

        if (widget.floating) {
            widget.focusOnToFront = false;
        }

        if (entry.setup) {
            entry.setup.call(widget, widget, ct);
        }
        else {
            widget.render(ct);

            if (widget.floating) {
                widget.showAt(0, 0);
                ct.setHeight(widget.getHeight());
            }
        }

        var el = widget.el;

        if (entry.delegate) {
            el = el.down(entry.delegate);
        }

        el.addCls('x-slicer-target'); // this is what generateSlicerManifest looks for

        if (entry.over) {
            widget.addOverCls();
        }

        if (config.parentCls) {
            el.parent().addCls(config.parentCls);
        }

        if (Ext.theme.legacy) {
            // The 4.1 approach has some interesting extra pieces
            //
            var data = {};

            if (entry.reverse) {
                data.reverse = true;
            }

            if (entry.filename) {
                data.filename = entry.filename;
            }

            if (entry.folder) {
                data.folder = entry.folder;
            }

            if (entry.offsets) {
                data.offsets = entry.offsets;
            }

            Ext.theme.setData(el.dom, data);
        }

        Ext.theme.currentWidget = null;
    },

    /**
     * Renders all of the components that have been added to the manifest.
     * @private
     */
    render: function() {
        console.log("rendering widgets...");
        var manifest = Ext.theme._manifest,
            shortcuts = Ext.theme._shortcuts;

        for (var k = 0, n = manifest ? manifest.length : 0; k < n; ++k) {
            var manifestEntry = manifest[k],
                xtype = Ext.theme.addWidget(manifestEntry.xtype),
                widgetShortcuts = xtype ? shortcuts[xtype] : null;

            if (xtype && manifestEntry.ui && widgetShortcuts) {
                for (var i = 0; i < widgetShortcuts.length; i++) {
                    Ext.theme.renderWidget(manifestEntry, widgetShortcuts[i]);
                }
            }
            else {
                Ext.theme.renderWidget(manifestEntry);
            }
        }
    },

    /**
     * Renders all components (see `render`) and notifies the Slicer that things are ready.
     * @private
     */
    run: function() {
        var extjsVer = Ext.versions.extjs,
            globalData = {};

        if (Ext.layout.Context) {
            Ext.override(Ext.layout.Context, {
                run: function() {
                    var ok = this.callParent(),
                        widget = Ext.theme.currentWidget;

                    if (!ok && widget) {
                        Ext.Error.raise("Layout run failed: " + widget.id);
                    }

                    return ok;
                }
            });
        }

        console.log("loading widget definitions...");

        // Previous to Ext JS 4.2, themes and their manifests where defined differently.
        // So pass this along if we are hosting a pre-4.2 theme.
        //
        if (extjsVer && extjsVer.isLessThan(new Ext.Version("4.2"))) {
            globalData.format = "1.0"; // tell the Slicer tool
            Ext.theme.legacy = true; // not for our own data collection

            // Check for the Cmd3.0/ExtJS4.1 variables:
            //
            if (Ext.manifest && Ext.manifest.widgets) {
                Ext.theme.addManifest(Ext.manifest.widgets);
            }

            if (Ext.shortcuts) {
                Ext.theme.addShortcuts(Ext.shortcuts);
            }

            if (Ext.userManifest && Ext.userManifest.widgets) {
                Ext.theme.addManifest(Ext.userManifest.widgets);
            }
        }

        Ext.theme.setData(document.body, globalData);
        Ext.theme.render();
        Ext.theme.generateSlicerManifest();
    },

    generateSlicerManifest: function() {
        var now = new Date().getTime(),
            me = Ext.theme,
            // This function is defined by slicer.js (the framework-independent piece)
            gsm = window && window.generateSlicerManifest,
            delta;

        me.generateStart = me.generateStart || now;
        delta = now - me.generateStart;

        if (gsm) {
            gsm();
        }
        else if (delta < (10 * 1000)) {
            // allow the outer script wrapper a chance to inject the capture function
            // but stop trying after 10 seconds
            Ext.defer(Ext.theme.generateSlicerManifest, 100);
        }
    },

    /**
     * Sets the `data-slicer` attribute to the JSON-encoded value of the provided data.
     * @private
     */
    setData: function(el, data) {
        if (data) {
            var json = Ext.encode(data);

            if (json !== '{}') {
                el.setAttribute('data-slicer', json);
            }
        }
    },

    /**
     * This used to be `loadExtStylesheet`.
     * @private
     */
    loadCss: function(src, callback) {
        var xhr = new XMLHttpRequest();

        xhr.open('GET', src);

        xhr.onload = function() {
            var css = xhr.responseText,
                head = document.getElementsByTagName('head')[0],
                style = document.createElement('style');

            // There's bugginess in the next gradient syntax in WebKit r84622
            // This might be fixed in a later WebKit, but for now we're going to
            // strip it out here since compass generates it.
            //
            // TODO: Upgrade to later WebKit revision
            css = css.replace(/background(-image)?: ?-webkit-linear-gradient(?:.*?);/g, '');

            style.type = 'text/css';
            style.innerText = css;

            head.appendChild(style);
            callback();
        };

        xhr.send(null);
    }
});

console.log("registering ready listener...");
Ext.onReady(Ext.theme.run, Ext.theme);
