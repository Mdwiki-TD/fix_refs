<?PHP

namespace WpRefs\csrf;
/*
Usage:
include_once __DIR__ . '/csrf.php';

use function WpRefs\csrf\generate_csrf_token;
use function WpRefs\csrf\verify_csrf_token; // if (verify_csrf_token())  {
*/

if (session_status() === PHP_SESSION_NONE) {
	// 	session_name("mdwikitoolforgeoauth");
	session_start();
}

function verify_csrf_token()
{
	// return true;
	// ---
	$csrf_key = "csrf_tokens";
	// ---
	// التحقق مما إذا كان هناك CSRF Tokens في الجلسة
	if (!isset($_SESSION[$csrf_key]) || !is_array($_SESSION[$csrf_key])) {
		$_SESSION[$csrf_key] = [];
		echo "No csrf tokens in session!";
		return true;
	}

	// التحقق من وجود Token في الطلب
	$submitted_token = $_POST['csrf_token'] ?? null;

	// إذا لم يتم إرسال Token، فإن التحقق يفشل
	if (!$submitted_token) {
		echo "No CSRF Token Submitted!";
		return false;
	}

	// التحقق مما إذا كان Token موجودًا في القائمة
	if (in_array($submitted_token, $_SESSION[$csrf_key], true)) {
		// Token صحيح، إزالته من القائمة
		$_SESSION[$csrf_key] = array_diff($_SESSION[$csrf_key], [$submitted_token]);

		// تحديث الجلسة بعد التعديل
		// session_write_close();

		// echo "Valid CSRF Token!";
		return true;
	} else {
		// echo "Invalid or Reused CSRF Token!";
		// ---
		echo <<<HTML
				<div class='alert alert-danger' role='alert'>
					Invalid or Reused CSRF Token!
				</div>
				HTML;
		// ---
		return false;
	}
	// ---
	echo <<<HTML
		<div class='alert alert-danger' role='alert'>
			This page is not allowed to be opened directly!
		</div>
	HTML;
	// ---
	return false;
}

function generate_csrf_token()
{
	$token = bin2hex(random_bytes(32));
	if (!isset($_SESSION['csrf_tokens'])) {
		$_SESSION['csrf_tokens'] = [];
	}
	$_SESSION['csrf_tokens'][] = $token; // إضافة Token جديد إلى القائمة
	return $token;
}
