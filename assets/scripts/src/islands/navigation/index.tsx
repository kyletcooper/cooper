import cn from "@/util/cn";
import { Island } from "@wrdagency/react-islands";
import { FC } from "react";

interface NavigationProps {
	className?: string;
}

const Navigation: FC<NavigationProps> = ({ className }) => {
	return <nav className={cn("nav", className)}>Navigation</nav>;
};

export default new Island(Navigation);
