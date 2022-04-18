import Vue from 'vue'
import VueI18n from 'vue-i18n'
Vue.use(VueI18n);
// Definir identificador
const i18n = new VueI18n({
    locale: 'en-US',    // ID de idioma
    //this.$i18n.locale // El cambio de idioma se logra cambiando el valor de la configuración regional
    
});
export {i18n}