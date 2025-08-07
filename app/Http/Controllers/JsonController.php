<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Method that receives an JSON on an request and returns a CSV file.
 *
 * @param $request (Request)
 */
class JsonController extends Controller
{
    function upload(Request $request)
    {
        // Validate the JSON request data
        $validator = Validator::make($request->all(), [
            "json_data" => ["required", "json", "max:1048576"],
        ]);

        // If validation fails, return an error response with error 422
        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid JSON data, try another JSON",
                    "errors" => $validator->errors(),
                ],
                422,
            );
        }

        // Try to decode the JSON data
        try {
            $data = json_decode(
                $request->json_data,
                true,
                2,
                JSON_THROW_ON_ERROR,
            );

            // Return an error to front end if the JSON is empty
            if (empty($data)) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Empty JSON data",
                    ],
                    422,
                );
            }

            // Convert to CSC
            $csv = $this->jsonToCsv($data);

            return response()->json([
                "success" => true,
                "csv" => $csv,
            ]);

            // Catch any error on JSON
        } catch (\JsonException $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid JSON format: " . $e->getMessage(),
                ],
                400,
            );

            // Catch any other error
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => $e->getMessage(),
                ],
                400,
            );
        }
    }

    /**
     * Converts JSON to CSV format
     * Returns error if JSON has more than one level (nested objects/arrays)
     *
     * @param array $data JSON string to convert
     */
    private function jsonToCsv(array $data): string
    {
        // If it's not an array of objects, convert single object to array
        if (
            !is_array($data) ||
            (is_array($data) && !is_numeric(array_keys($data)[0]))
        ) {
            $data = [$data];
        }

        // Check for nested structures (more than one level)
        foreach ($data as $row) {
            if (!is_array($row)) {
                return [
                    "success" => false,
                    "data" => null,
                    "message" =>
                        "JSON must contain objects/arrays at the root level",
                ];
            }

            foreach ($row as $key => $value) {
                // Check if value is an array or object (nested structure)
                if (is_array($value) || is_object($value)) {
                    return [
                        "success" => false,
                        "data" => null,
                        "message" => "Nested structure detected in field '{$key}'. Only flat JSON structures are allowed.",
                    ];
                }
            }
        }

        // Generate CSV
        $csv = "";
        $headers = [];

        // Get all unique headers from all rows
        foreach ($data as $row) {
            $headers = array_merge($headers, array_keys($row));
        }
        $headers = array_unique($headers);

        // Add CSV headers
        $csv .=
            implode(
                ",",
                array_map(function ($header) {
                    return '"' . str_replace('"', '""', $header) . '"';
                }, $headers),
            ) . "\n";

        // Add CSV rows
        foreach ($data as $row) {
            $csvRow = [];
            foreach ($headers as $header) {
                $value = isset($row[$header]) ? $row[$header] : "";
                // Escape quotes and wrap in quotes if necessary
                if (is_null($value)) {
                    $csvRow[] = "";
                } else {
                    $value = (string) $value;
                    $csvRow[] = '"' . str_replace('"', '""', $value) . '"';
                }
            }
            $csv .= implode(",", $csvRow) . "\n";
        }

        return $csv;
    }
}
