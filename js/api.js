"use strict";

class Api {

    /**
     * Api
     */
    constructor(dataSource) {
        /**
         * Data source type
         * @type {string}
         */
        this.dataSource = dataSource;
        this.items = {};
    }

    /**
     * Get items from data source
     * @returns {Promise<any>}
     */
    async getData() {
        const response = await fetch(`/api/${this.dataSource}`);
        return response.json();
    }

    /**
     * Delete item from data source by key
     * @param key
     * @returns {Promise<any>}
     */
    async deleteData(key) {
        const response = await fetch(`/api/${this.dataSource}/${key}`, {
            "method": "DELETE"
        });
        return response.json();
    }

    /**
     * Render list of items
     * @param container
     */
    render(container) {
        const block = document.querySelector(container);
        if (!block) {
            console.log(`Container ${container} didn't find`);
            return;
        }

        this.getData().then(result => {
            if (result.code === 200) {
                const ulEl = document.createElement("ul");
                ulEl.classList.add("items-list");
                for (let [key, value] of Object.entries(result.data)) {
                    const liEl = document.createElement("li");
                    liEl.innerHTML = `{${key}}: {${value}} <a href='#' class='remove'>delete</a>`;
                    this.items[key] = liEl;
                    ulEl.appendChild(liEl);
                }
                block.appendChild(ulEl);
                ulEl.addEventListener("click", this.deleteItem.bind(this));
            }
        }).catch(() => {
            block.innerHTML = "<h2>No items</h2>";
        });
    }

    /**
     * Event when deleting item
     * @param event
     */
    deleteItem(event) {
        if (event.target.classList.contains("remove")) {
            event.preventDefault();
            const liContext = event.target.closest("li").innerHTML;
            let key = liContext.match(/{(.*)}:/);
            key = key.pop();
            if (key) {
                this.deleteData(key).then(result => {
                    if (result.code === 200) {
                        const liEl = this.items[key];
                        if (liEl) {
                            const ulEl = document.querySelector(".items-list");
                            ulEl.removeChild(liEl);
                            delete this.items[key];
                            if(Object.keys(this.items).length === 0) {
                                ulEl.parentElement.innerHTML = "<h2>No items</h2>";
                                ulEl.remove();
                            }
                        }
                    }
                }).catch(() => {
                    console.log("Bad request");
                });
            }
        }
    }
}