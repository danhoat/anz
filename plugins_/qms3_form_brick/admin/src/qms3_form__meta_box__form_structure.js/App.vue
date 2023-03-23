<template>
  <div class="qms3_form__meta_box__form_structure__app">
    <FormStructureTable v-model:form-structure="formStructure" />

    <input
      type="hidden"
      name="qms3_form__meta_box__form_structure"
      :value="formStructureJson"
    />

    <div
      class="qms3_form__meta_box__form_structure__debug"
      v-if="debug"
    >
      <pre><code style="display:block;overflow-x:scroll">{{ formStructure }}</code></pre>
    </div>
    <!-- /.qms3_form__meta_box__form_structure__debug -->
  </div>
  <!-- /.qms3_form__meta_box__form_structure__app -->
</template>

<script>
import { computed, defineComponent, ref } from 'vue';
import FormStructureTable from './components/FormStructureTable.vue';
import { presets } from './formStructure';

export default defineComponent({
  components: {
    FormStructureTable,
  },

  setup() {
    const formStructureRef = QMS3_FORM__META_BOX__FORM_STRUCTURE.length
      ? ref(QMS3_FORM__META_BOX__FORM_STRUCTURE)
      : ref(presets['お名前・ふりがな']);

    const formStructureJson = computed(() => JSON.stringify(formStructureRef.value))

    return {
      formStructure: formStructureRef,
      formStructureJson,

      debug: /\bdebug\b/.test(location.search),
    };
  },
})
</script>
