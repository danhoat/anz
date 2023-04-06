(() => {
    "use strict";
    var e = {
        n: t => {
            var l = t && t.__esModule ? () => t.default : () => t;
            return e.d(l, {
                a: l
            }), l
        },
        d: (t, l) => {
            for (var s in l) e.o(l, s) && !e.o(t, s) && Object.defineProperty(t, s, {
                enumerable: !0,
                get: l[s]
            })
        },
        o: (e, t) => Object.prototype.hasOwnProperty.call(e, t)
    };
    const t = window.wp.element,
        l = window.wp.blocks,
        s = window.lodash,
        o = window.wp.components,
        n = window.wp.i18n,
        a = window.wp.blockEditor,
        c = window.wp.data,
        r = e => {
            let {
                onChange: l,
                value: s,
                filter: a = (() => !0)
            } = e;
            const r = (0, c.useSelect)((e => e("core").getPostTypes({
                per_page: -1
            }) || []), []).filter((e => !["attachment", "wp_block"].includes(e.slug))).filter(a);
            return (0, t.createElement)(o.SelectControl, {
                label: (0, n.__)("Post Type", "advanced-posts-blocks"),
                value: s.slug,
                options: r.map((e => ({
                    label: e.name,
                    value: e.slug
                }))),
                onChange: e => {
                    const t = r.find((t => t.slug === e));
                    l(t)
                }
            })
        },
        i = JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","name":"advanced-posts-blocks/post","apiVersion":2,"title":"Single Post","description":"Display single post.","icon":"admin-post","textdomain":"advanced-posts-blocks","attributes":{"align":{"type":"string","enum":["center","wide","full"]},"postType":{"type":"string","default":"post"},"postId":{"type":"number","default":0}},"supports":{"align":["center","wide","full"],"html":false},"category":"widgets","editorScript":"file:./index.js"}'),
        p = e => e.flatMap((e => e ? e.split(" ") : [])),
        d = window.wp.serverSideRender;
    var u = e.n(d);

    function m(e) {
        let {
            title: l,
            children: s
        } = e;
        return (0, t.createElement)(o.Placeholder, {
            label: l
        }, s)
    }
    const g = (0, t.memo)((e => {
            let {
                name: l,
                attributes: s,
                title: n,
                emptyResponseLabel: a
            } = e;
            return (0, t.createElement)(u(), {
                block: l,
                attributes: s,
                LoadingResponsePlaceholder: () => (0, t.createElement)(m, null, (0, t.createElement)(o.Spinner, null)),
                EmptyResponsePlaceholder: () => (0, t.createElement)(m, {
                    title: n
                }, a)
            })
        })),
        b = g,
        {
            name: w,
            title: v
        } = i,
        {
            name: y,
            supports: h,
            category: k
        } = i,
        f = (0, n.__)("Single Post", "advanced-posts-blocks");
    (0, l.registerBlockType)(y, {
        supports: h,
        category: k,
        title: `${f} (Advanced Posts Blocks)`,
        description: (0, n.__)("Display single post.", "advanced-posts-blocks"),
        keywords: ["single post", (0, n.__)("single post", "advanced-posts-blocks")],
        icon: (0, t.createElement)(o.SVG, {
            width: "24",
            height: "24",
            viewBox: "0 0 24 24",
            fill: "none",
            xmlns: "http://www.w3.org/2000/svg"
        }, (0, t.createElement)(o.Path, {
            d: "M7 18H17V16H7V18Z",
            fill: "black"
        }), (0, t.createElement)(o.Path, {
            d: "M17 14H7V12H17V14Z",
            fill: "black"
        }), (0, t.createElement)(o.Path, {
            d: "M7 10H11V8H7V10Z",
            fill: "black"
        }), (0, t.createElement)(o.Path, {
            "fill-rule": "evenodd",
            "clip-rule": "evenodd",
            d: "M6 2C4.34315 2 3 3.34315 3 5V19C3 20.6569 4.34315 22 6 22H18C19.6569 22 21 20.6569 21 19V9C21 5.13401 17.866 2 14 2H6ZM6 4H13V9H19V19C19 19.5523 18.5523 20 18 20H6C5.44772 20 5 19.5523 5 19V5C5 4.44772 5.44772 4 6 4ZM15 4.10002C16.6113 4.4271 17.9413 5.52906 18.584 7H15V4.10002Z",
            fill: "black"
        })),
        edit: e => {
            let {
                attributes: i,
                setAttributes: d
            } = e;
            const [u, m] = (0, t.useState)(""), {
                postId: g,
                className: y,
                postType: h
            } = i, k = (f = h, (0, c.useSelect)((e => e("core").getPostType(f) || {}), [f]));
            var f;
            const _ = ((e, t) => (0, c.useSelect)((l => t ? l("core").getEntityRecord("postType", e.slug, t) : null), [e, t]))(k, g);
            let E = ((e, t) => (0, c.useSelect)((l => "" === t.search ? [] : l("core").getEntityRecords("postType", e.slug, t) || []), [e.slug, t]))(k, {
                per_page: -1,
                advanced_posts_blocks: !0,
                search: u
            });
            _ && (E = (0, s.uniqBy)([_, ...E], "id"));
            const P = (0, t.createElement)(o.ComboboxControl, {
                    help: (0, n.__)("Select post", "advanced-posts-blocks"),
                    label: (0, n.__)("Post", "advanced-posts-blocks"),
                    value: g,
                    options: [...E.map((e => ({
                        label: `${e.title.rendered} (ID: ${e.id})`,
                        value: e.id
                    })))],
                    onFilterValueChange: e => {
                        m(e)
                    },
                    onChange: e => {
                        d({
                            postId: e ? parseInt(e) : void 0
                        })
                    }
                }),
                C = (0, t.createElement)(a.InspectorControls, null, (0, t.createElement)(o.PanelBody, {
                    title: (0, n.__)("Query setting", "advanced-posts-blocks")
                }, (0, t.createElement)(r, {
                    value: k,
                    onChange: e => {
                        d({
                            postId: void 0
                        }), d({
                            postType: e.slug
                        })
                    }
                }), P)),
                V = (0, l.getBlockDefaultClassName)(w),
                S = function(e) {
                    const t = p(arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : []),
                        {
                            className: l
                        } = e,
                        s = l ? l.split(" ") : [];
                    return {
                        ...e,
                        className: s.filter((e => !t.includes(e))).join(" ")
                    }
                }((0, a.useBlockProps)(), [V, y]);
            return (0, t.createElement)("div", S, C, (0, t.createElement)(o.Disabled, null, (0, t.createElement)(b, {
                name: w,
                attributes: i,
                title: v,
                emptyResponseLabel: (0, n.__)("Post Not Found.", "advanced-posts-blocks")
            })))
        },
        save: () => null
    })
})();