"use strict";
class Trie {
    
    constructor() {
        this.root = new Node('');
    }

    add(word) {
        let parent = this.root;

        let child;
        let index = 0;
        let wordLen = word.length - 1;

        for(let letter of word) {
            child = parent.getChild(letter);
            if(!child) {
                child = parent.addChild(letter);
            }

            if(wordLen <= index) {
                child.isWord = true;
            }
            
            parent = child;
            index++;
        }
    }
}


// function Trie() {
//     var trie = {
//         root: new Node(''),
//         add: function(word) {
//             parent = trie.root;

//             let wordLen = word.length - 1;
//             let index = 0;
//             let child;
//             for(const letter of word) {

//                 child = parent.getChild(letter);
//                 if(!child) {
//                     child = parent.addChild(letter);
//                 }
//                 if(wordLen <= index) {
//                     child.isWord = true;
//                 }
//                 parent = child;
//                 index++;
//             }
//         }
//     };
//     return trie;
// }

// var Trie = function() {
//     var trie = {
//         root: Node(''),
//         add: function(word) {
//             parent = trie.root;

//             let wordLen = word.length - 1;
//             let index = 0;
//             let child;
//             for(const letter of word) {

//                 child = parent.getChild(letter);
//                 if(!child) {
//                     child = parent.addChild(letter);
//                 }
//                 if(wordLen <= index) {
//                     child.isWord = true;
//                 }
//                 parent = child;
//                 index++;
//             }
//         }
//     };
//     return trie;
// };
