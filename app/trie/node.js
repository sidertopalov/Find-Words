"use strict";

class Node {
    constructor(value) {
        this.value = value;
        this.isWord = false;
        this.childrens = {};
    }

    addChild(letter) {
        let child = new Node(this.value + letter);
        this.childrens[letter] = child;

        return child;
    }

    getChild(letter) {
        if(this.childrens.hasOwnProperty(letter)) {
            return this.childrens[letter];
        }
        return false;
    }
}

// function Node(value) {
//     var node = {
//         value: value,
//         isWord: false,
//         children: {},
//         getChild: function(letter) {
//             if(node.children.hasOwnProperty(letter)) {
//                 return node.children[letter];
//             }
//         },
//         addChild: function(letter) {
//             const child = Node(node.value + letter);
//             node.children[letter] = child;
//             return child;
//         }
//     };
//     return node;
// }

// var Node = function(value) {
//     var node = {
//         value: value,
//         isWord: false,
//         children: {},
//         getChild: function(letter) {
//             if(node.children.hasOwnProperty(letter)) {
//                 return node.children[letter];
//             }
//         },
//         addChild: function(letter) {
//             const child = Node(node.value + letter);
//             node.children[letter] = child;
//             return child;
//         }
//     };
//     return node;
// };
