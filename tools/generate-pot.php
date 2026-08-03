<?php
/**
 * Generate a .pot file from all translatable strings in the plugin.
 *
 * Usage: php generate-pot.php > ../languages/eventin-beaver-themer.pot
 *
 * @internal — not part of the plugin distribution.
 */

$root   = dirname( __DIR__ );
$domain = 'eventin-beaver-themer';

$strings = []; // msgid => [ 'refs' => [ file:line, ... ], 'is_esc' => bool ]

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $root, RecursiveDirectoryIterator::SKIP_DOTS )
);

foreach ( $iterator as $file ) {
	if ( $file->getExtension() !== 'php' ) {
		continue;
	}

	$relpath = str_replace( $root . '/', '', $file->getPathname() );

	// Skip this script itself and reference material.
	if ( strpos( $relpath, '_reference/' ) === 0 || strpos( $relpath, 'tools/' ) === 0 ) {
		continue;
	}

	$lines = file( $file->getPathname() );

	foreach ( $lines as $index => $line ) {
		$lineno = $index + 1;

		// Match __( '...', 'eventin-beaver-themer' ) and esc_html__( '...', 'eventin-beaver-themer' )
		if ( preg_match_all( "/(?:esc_html_)?__\(\s*'((?:[^'\\\\]|\\\\.)*)',\s*'{$domain}'\s*\)/", $line, $matches ) ) {
			foreach ( $matches[1] as $msgid ) {
				$msgid = str_replace( "\\'", "'", $msgid );
				if ( ! isset( $strings[ $msgid ] ) ) {
					$strings[ $msgid ] = [ 'refs' => [], 'is_esc' => false ];
				}
				$ref = $relpath . ':' . $lineno;
				if ( ! in_array( $ref, $strings[ $msgid ]['refs'], true ) ) {
					$strings[ $msgid ]['refs'][] = $ref;
				}
				if ( strpos( $line, 'esc_html__' ) !== false ) {
					$strings[ $msgid ]['is_esc'] = true;
				}
			}
		}
	}
}

// Sort alphabetically by msgid.
ksort( $strings );

// Output .pot
$date = date( 'Y-m-d H:iO' );

echo <<<POT
# Copyright (C) 2026 One Dog Solutions
# This file is distributed under the GPL-2.0+.
msgid ""
msgstr ""
"Project-Id-Version: Beaver Themer for Eventin 1.0.0\\n"
"Report-Msgid-Bugs-To: https://github.com/onedogsolutions/beaver-themer-for-eventin/issues\\n"
"POT-Creation-Date: {$date}\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"
"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"
"Language-Team: LANGUAGE <LL@li.org>\\n"


POT;

foreach ( $strings as $msgid => $meta ) {
	foreach ( $meta['refs'] as $ref ) {
		echo "#: {$ref}\n";
	}

	// Escape for PO format.
	$escaped = str_replace( [ '\\', '"' ], [ '\\\\', '\\"' ], $msgid );
	echo 'msgid "' . $escaped . '"' . "\n";
	echo 'msgstr ""' . "\n";
	echo "\n";
}
