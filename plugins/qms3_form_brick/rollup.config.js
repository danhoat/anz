import { babel } from '@rollup/plugin-babel'
import commonjs from '@rollup/plugin-commonjs'
import { nodeResolve } from '@rollup/plugin-node-resolve'
import replace from '@rollup/plugin-replace'
import { terser } from 'rollup-plugin-terser'
import vue from 'rollup-plugin-vue'

export default [
  {
    input: 'src/main.js',

    external: [ 'jquery' ],

    plugins: [
      nodeResolve({
        browser: true,
      }),
      commonjs(),
      babel({
        presets: [['@babel/preset-env', {"modules": false}]],
        targets: { ie: 11 },
        plugins: [
          '@babel/plugin-transform-object-assign',
        ],
      })
    ],

    output: [
      // 実案件用
      {
        file: './qms3_form.js',
        format: 'iife',
        plugins: [],
        globals: { jquery: 'jQuery' },
      },

      // 実案件用 圧縮版
      {
        file: './qms3_form.min.js',
        format: 'iife',
        plugins: [ terser() ],
        globals: { jquery: 'jQuery' },
      },

      // デモ環境用
      {
        file: './demo/js/qms3_form.js',
        format: 'iife',
        plugins: [],
        globals: { jquery: 'jQuery' },
      },
    ],
  },

  {
    input: 'admin/src/qms3_form__submenu_page__brick_master.js/main.js',

    external: [ 'jspreadsheet-ce' ],

    plugins: [
      replace({
        preventAssignment: true,
        'process.browser': true,
        'process.env.NODE_ENV': JSON.stringify('production'),
      }),
      nodeResolve({ browser: true }),
      vue({ preprocessStyles: true }),
      commonjs(),
    ],

    output: [
      // 実案件用
      {
        file: './admin/assets/js/qms3_form__submenu_page__brick_master.js',
        format: 'iife',
        plugins: [],
      },

      // 実案件用 圧縮版
      {
        file: './admin/assets/js/qms3_form__submenu_page__brick_master.min.js',
        format: 'iife',
        plugins: [ terser() ],
      },
    ],
  },

  {
    input: 'admin/src/qms3_form__meta_box__mail_settings.js/main.js',

    external: [ 'jspreadsheet-ce' ],

    plugins: [
      replace({
        preventAssignment: true,
        'process.browser': true,
        'process.env.NODE_ENV': JSON.stringify('production'),
      }),
      nodeResolve({ browser: true }),
      vue({ preprocessStyles: true }),
      commonjs(),
    ],

    output: [
      // 実案件用
      {
        file: './admin/assets/js/qms3_form__meta_box__mail_settings.js',
        format: 'iife',
        plugins: [],
      },

      // 実案件用 圧縮版
      {
        file: './admin/assets/js/qms3_form__meta_box__mail_settings.min.js',
        format: 'iife',
        plugins: [ terser() ],
      },
    ],
  },

  {
    input: 'admin/src/qms3_form__meta_box__form_structure.js/main.js',

    external: [ 'jspreadsheet-ce' ],

    plugins: [
      replace({
        preventAssignment: true,
        'process.browser': true,
        'process.env.NODE_ENV': JSON.stringify('production'),
      }),
      nodeResolve({ browser: true }),
      vue({ preprocessStyles: true }),
      commonjs(),
    ],

    output: [
      // 実案件用
      {
        file: './admin/assets/js/qms3_form__meta_box__form_structure.js',
        format: 'iife',
        plugins: [],
      },

      // 実案件用 圧縮版
      {
        file: './admin/assets/js/qms3_form__meta_box__form_structure.min.js',
        format: 'iife',
        plugins: [ terser() ],
      },
    ],
  },
]
