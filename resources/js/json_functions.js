document.addEventListener("alpine:init", () => {
    Alpine.data("converter", () => ({
        jsonData: "",
        csvData: "",
        error: "",
        success: false,
        copyStatus: "",
        tableData: [],
        tableHeaders: [],

        uploadJson() {
            this.error = "";
            this.success = false;

            if (!this.jsonData.trim()) {
                this.error = "Please enter JSON data";
                return;
            }

            axios
                .post("/upload", {
                    json_data: this.jsonData,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.csvData = response.data.csv;
                        this.success = true;
                        this.parseCsvForTable(response.data.csv);
                    } else {
                        this.error = response.data.message;
                    }
                })
                .catch((error) => {
                    if (error.response) {
                        this.error =
                            error.response.data.message ||
                            "An error occurred during conversion";
                    } else {
                        this.error = "Network error. Please try again.";
                    }
                });
        },

        // New method to parse CSV for table display
        parseCsvForTable(csv) {
            const lines = csv.split("\n");
            if (lines.length === 0) return;

            // Get headers from first line
            this.tableHeaders = this.parseCsvLine(lines[0]);

            // Process remaining lines
            this.tableData = lines
                .slice(1)
                .filter((line) => line.trim() !== "")
                .map((line) => {
                    const values = this.parseCsvLine(line);
                    const row = {};
                    this.tableHeaders.forEach((header, index) => {
                        row[header] = values[index] || "";
                    });
                    return row;
                });
        },

        // Helper method to parse CSV line (handles quoted values)
        parseCsvLine(line) {
            const pattern = /(?:,|^)(?:"([^"]*)"|([^",]*))/g;
            const values = [];
            let match;

            while ((match = pattern.exec(line))) {
                values.push(match[1] || match[2] || "");
            }

            return values;
        },

        // Copy to clipboard
        copyToClipboard() {
            if (!navigator.clipboard) {
                alert("Clipboard API not supported in this browser.");
                return;
            } else if (!this.csvData) {
                alert("No data to copy.");
                return;
            } else {
                navigator.clipboard.writeText(this.csvData);
                alert("CSV data copied to clipboard!");
            }
        },

        // Function to download the CSV data from table
        downloadCSV() {
            if (!this.csvData) {
                alert("No data to download.");
                return;
            } else {
                const blob = new Blob([this.csvData], { type: "text/csv" });
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "converted_data.csv";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                alert("CSV file downloaded successfully!");
            }
        },
    }));
});
