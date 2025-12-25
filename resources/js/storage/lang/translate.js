import { defineStore } from "pinia";
import wordsRu from "@/lang/ru/ru.js";
import wordsEn from "@/lang/en/en.js";
const availableLang = ["en", "ru"];
export const useTranslateStore = defineStore ('translate', {
    state: () => ({
        currentLang: "en"
    }),
    actions: {
        t(word) {
            if(!availableLang.includes(this.currentLang)){
                return "Верни как было, сука";
            }
            switch (this.currentLang){
                case "ru":
                    return wordsRu[word];
                case "en":
                    return wordsEn[word];
            }
        }
    },
    persist: true
});
