<template>
    <div translate="no">
        <div class="story-image">
            <img v-if="story.front_image_url" :src="'/storage/' + story.front_image_url"/>
        </div>
        <div class="story">
            <p class="small">Click on a sentence to get the translation.</p>
            <h1 class="text-center mb-4">{{story.title}}</h1>
            <div v-if="story.description" class="description">
                {{story.description}}
            </div>
            <div class="story-text">
                <span v-for="i in phrases.length">
                    <span v-html="phrases[i - 1]" :id="'phrase-'+(i-1)" class="phrase"></span>
                    <div v-if="i <= translatedPhrases.length" :id="'translation-'+(i-1)" style="display: none;"  class="translation">
                        <md-icon>arrow_upward</md-icon>
                        <span v-html="translatedPhrases[i - 1]"></span>
                    </div>

                </span>

            </div>

            <div v-if="story.vocab.length > 0" class="vocab-container">
                <h3>Vocabulary in this story</h3>
                <table class="table vocab-table">
                    <thead>
                    <tr>
                        <th>Word</th>
                        <th>Reading</th>
                        <th>Meaning</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="vocab in story.vocab" :key="vocab.id">
                        <td>
                            {{vocab.word}}
                        </td>
                        <td>
                            {{vocab.reading}}
                        </td>
                        <td>
                            {{vocab.meaning}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
    import marked from 'marked'
    export default {
        name: "StoryReader",
        props: {
            story: {
                type: Object,
                required: true
            }
        },
        data: function(){
            return {
                formatedContent: "",
                phrases: [],
                translatedPhrases: [],
            }
        },
        methods:{
            translate: function(japanese){
                return this.story.translations.filter(translation => translation.japanese === japanese)[0].translation;
            }
        },
        mounted() {
            this.formatedContent = marked(this.story.content);
            this.phrases = this.formatedContent.split('\n');

            if(this.story.translated_content) {
                let formatedTranslation = marked(this.story.translated_content);
                this.translatedPhrases = formatedTranslation.split('\n');
                this.translatedPhrases = this.translatedPhrases.filter(phrase => phrase !== '')
            }

            // Setup the click to get translation feature
            let self = this;

            $("body").delegate(".phrase p", 'click', function() {
                let phrase_id = $(this).closest('span').attr('id');
                let index = parseInt(phrase_id.split('-')[1]);

                // If the content of the translation is [image], dont display it.

                let translation = $("#translation-" + index);
                console.log(translation.text());
                if(!translation.text().includes('[image]')) {
                    translation.toggle()
                }
            });
        }
    }
</script>

<style scoped>
    .story-image > img{
        width:100%;
        transform: translateY(-20%);
        filter: brightness(0.5);
    }
    .story-text{
        font-size:1.6em;
        font-family: notosans, "Nunito", sans-serif;
        letter-spacing: 2px;
    }

    .description{
        border-bottom: 1px solid #325259;
        padding: 0px 20px 20px 20px;
        font-size: 1.3em;
        margin-bottom: 20px;
    }

    /deep/ .story-text img{
        width:100%;
    }

    /deep/ .phrase{
        cursor: pointer;
    }

    /deep/ .translation{
        width: 100%;
        height: fit-content;
        background-color: #f2f2f2;
        color: #325259;
        display: block;
        padding: 10px;
        margin-bottom: 15px;
    }

    /deep/ .translation p{
        margin:0;
        display:inline-block;
    }

    .vocab-table{
        border-collapse:collapse;
    }

    .vocab-table td{
        font-size:1.5em;
    }
</style>
