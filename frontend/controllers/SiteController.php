<?php
namespace frontend\controllers;

use common\models\db\Settings;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\modules\api\models\auth\PasswordRestoreForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\db\ConfirmEmailRequest;
use common\models\db\User;
use common\models\db\Page;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordRestoreForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->restorePassword()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
        	/**
        	 * @var ResetPasswordForm $model
        	 */
        	$model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'Новый пароль сохранен');
        		return $this->redirect('/');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось изменить пароль');
            }
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    /**
     * Подтверждение email при регистрации через логин и пароль.
     *
     * @param string $token
     * @return boolean
     */
    public function actionConfirmEmail($token)
    {
    	/**
    	 * @var ConfirmEmailRequest $confirmEmailRequest
    	 */
    	$confirmEmailRequest = ConfirmEmailRequest::findByConfirmEmailToken($token);
    	if (empty($confirmEmailRequest)) {
    		return "Вы не можете быть зарегистрированы.";
    	}
    	
    	if ($confirmEmailRequest->isUsed == ConfirmEmailRequest::IS_USED) {
    		return "Вы уже подтверждали свой email.";
    	}
    	
    	/**
    	 * @var User $user
    	 */
    	$user = User::find()->where(['userId' => $confirmEmailRequest->userId])->one();
    	if (empty($user)) {
    		return "Вы не можете быть зарегистрированы.";
    	}
    	
    	$user->isEmailConfirmed = User::IS_EMAIL_CONFIRMED;
    	$confirmEmailRequest->isUsed = ConfirmEmailRequest::IS_USED;
    	if ($user->save() && $confirmEmailRequest->save()) {
    		return "Вы успешно зарегистрированы.";
    	}
    	
    	return "Вы не можете быть зарегистрированы.";
    }
    
    /**
     * Displays other page.
     * @param string $key
     * @return mixed
     */
    public function actionPage($key)
    {
    	$this->layout = "pageMain";
    	
    	/**
    	 * @var Page $page
    	 */
    	$page = Page::find()->where(['key' => $key])->one();
    	if (empty($page)) {
    		throw new NotFoundHttpException("Страница не найдена.");
    	}
    	return $this->render('page', [
    		'page' => $page,
    	]);
    }

    public function actionDeeplinkTest() {
        return $this->redirect('https://cosanostra2.whitetigersoft.ru/task/details', 303);
    }
}
