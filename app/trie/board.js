"use strict";

class BoardSearch {

    #_result = {};
    #_sortResult = true;
    #_neighboursCells = [
        [-1, -1],  [-1, 0],  [-1, 1],
        [0, -1],             [0, 1],
        [1, -1],   [1, 0],   [1, 1]
    ];

    /**
    * @param {Array} board - The board Multidimensional array represent board ex. 4x4
    * @param {Trie} words_trie - Dictionary from type Trie
    * @param {Boolean} sort - Sort result by length or not from larges to smaller words
    */
    constructor(board, words_trie, sort = true) {
        if(board && words_trie) {
            this.init(board, words_trie, sort);
        }
    }

    /**
    * @param {Array} board - The board Multidimensional array represent board(grid) ex. 4x4
    * @param {Trie} words_trie - Dictionary
    * @param {Boolean} sort - Sort result by length or not from larges to smaller words
    */
    init(board, words_trie, sort = true) {

        if(words_trie.constructor.name !== 'Trie') {
            throw new Error('Wrong argument(\'words_trie\') must be instance of \'Trie\'. ' + words_trie.constructor.name + ' is given!');
        }
        
        this._board = board;
        this._words_trie = words_trie;
        this.sortResult = sort;
    }

    _sortResult() {
        if(!Object.keys(this.#_result).length) {
            console.log('result is empty', this.#_result.length);
            return false;
        }

        let sortedKeys = Object.keys(this.#_result).sort(function(a, b) {
            return b.length - a.length;
        });
        let res = {};
        let self = this;
        sortedKeys.forEach(function(value) {
            res[value] = self.#_result[value];
        });
        this.#_result = res;
        return res;
    }
    
    _invokeCallback() {
        if(!$.isFunction(this._callback)) {
            return false;
        }
        this._callback(this.#_result);
        return true;
    }

    _inBoard(board, x, y) {
        return x >= 0 && y >= 0 && x < board.length && y < board.length;
    }

    /**
    * Using Breadth First Search (BFS) algorithm to find all posible combinations of characters in length between 3 <= 16
    * Using Trie Data Structure for max optimization this let us to search fast in large data
    */
    _searchBFS(pos, prefixNode, visitedPositions, path) {
        if(prefixNode.isWord && !this.#_result[prefixNode.value]) {
            this.#_result[prefixNode.value] = {
                value: prefixNode.value,
                visitedPositions: $.extend(true, {}, visitedPositions)
            };
        }
        path.push(pos);
        let self = this;
        this.#_neighboursCells.forEach(function(cords) {
            let cellPosition = [pos[0] + cords[0], pos[1] + cords[1]];
            if(self._inBoard(self._board, cellPosition[0], cellPosition[1]) && visitedPositions.containsArray(cellPosition) === -1) {

                let letter = self._board[cellPosition[0]][cellPosition[1]];
                let childNode = prefixNode.getChild(letter);

                if(!childNode || childNode === undefined) {
                    return;
                }
                
                visitedPositions.push(cellPosition);
                self._searchBFS(cellPosition, childNode, visitedPositions, path);
                visitedPositions.pop();

            }
        });
        path.pop(pos);
    }

    search(callback = null) {
        this._callback = callback;

        for(let row = 0; row < this._board.length; row++) {
            for(let col = 0; col < this._board.length; col++) {
                this._searchBFS([row, col], this._words_trie.root, [], []);
            }
        }
        
        if(this.sortResult) {
            this._sortResult();
        }
        if(!this._invokeCallback()) {
            return this.#_result;
        }
    }

    get result() {
        return this.#_result;
    }

    set sortResult(boolean) { 
        /**
        * @var boolean true = DESC false = ASC 
        */

        this.#_sortResult = boolean !== undefined ? boolean : true; 
    }

    get sortResult() {
        return this.#_sortResult;
    }

}