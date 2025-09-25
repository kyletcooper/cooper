export default function cn(
	...classes: (string | null | undefined | number | boolean)[]
) {
	return classes.filter(Boolean).join(" ");
}
